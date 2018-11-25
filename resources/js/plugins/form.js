import Errors from './errors';
import axios from 'axios';

export default class {
    constructor(data = {}, format = 'json') {
        this.errors = new Errors();
        this.submitting = false;
        this.__data = data;
        this.__multipart = format.toLowerCase() === 'multipart';
    }

    get __data() {
        let object = {};
        const ignoreAttributes = ['errors', 'submitting', '__multipart', '__data'];
        for (let attr in this) {
            if (ignoreAttributes.includes(attr)) continue;
            object[attr] = this[attr];
        }
        return object;
    }
    set __data(object) {
        for (let field in object) {
            this[field] = object[field];
        }
    }

    data() {
        return this.__multipart ? this.multipartData() : this.jsonData();
    }

    jsonData() {
        let data = {};

        for (let property in this.__data) {
            data[property] = this[property];
        }

        return data;
    }

    multipartData() {
        return this.toFormData(this.__data);
    }

    toFormData(obj, form, namespace) {
        let formData = form || new FormData();
        let formKey;

        for (let property in obj) {
            const value = obj[property];

            if (namespace) {
                formKey = namespace + '[' + property + ']';
            } else {
                formKey = property;
            }

            if (value instanceof Date) {
                formData.append(formKey, value.toISOString());
            } else if (typeof value === 'object' && !(value instanceof File)) {
                this.toFormData(value, formData, formKey);
            } else {
                formData.append(formKey, value);
            }
        }

        return formData;
    }

    reset() {
        for (let field in this.__data) {
            this[field] = '';
        }

        this.errors.clear();
    }

    post(url, fieldName = null) {
        return this.submit('post', url, fieldName);
    }

    put(url, fieldName = null) {
        if (this.__multipart) {
            this._method = 'PUT';
            return this.submit('post', url, fieldName);
        }
        return this.submit('put', url, fieldName);
    }

    patch(url, fieldName = null) {
        if (this.__multipart) {
            this._method = 'PATCH';
            return this.submit('post', url, fieldName);
        }
        return this.submit('patch', url, fieldName);
    }

    delete(url, fieldName = null) {
        return this.submit('delete', url, fieldName);
    }

    submit(requestType, url, fieldName = null) {
        const data = fieldName ? { [fieldName]: this.data() } : this.data();
        this.submitting = true;
        this.errors.clear();
        return new Promise((resolve, reject) => {
            axios[requestType](url, data)
                .then(response => {
                    this.submitting = false;

                    resolve(response);
                })
                .catch(error => {
                    this.submitting = false;
                    if (error.response && error.response.status === 422) this.onFail(error.response.data.errors);

                    reject(error.response);
                });
        });
    }

    onFail(errors) {
        this.errors.record(errors);
    }
}
