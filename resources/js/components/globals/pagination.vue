<template lang="pug">
    .d-flex.flex-column.align-items-center
        ul.pagination.justify-content-center.mb-1(v-if='meta && links')
            li.page-item(:class="{ 'disabled': !links.prev }")
                a.page-link(@click.prevent="$emit('page', meta.current_page - 1)", :disabled='!links.prev', tabindex='-1')
                    i.fa.fa-angle-left
            template(v-if="show_first_page_links")
                li.page-item
                    a.page-link(@click.prevent="$emit('page', 1)", tabindex='-1') 1
                li.page-item.disabled(v-if="show_first_ellipsis")
                    a.page-link ...
            li.page-item(:class="{'active': current(page)}", v-for='page of pages', :key='page')
                a.page-link(@click.prevent='onClick(page)', :disabled='current(page)', tabindex='-1') {{ page }}
            template(v-if='show_last_page_links')
                li.page-item.disabled(v-if="show_last_ellipsis")
                    a.page-link ...
                li.page-item
                    a.page-link(@click.prevent="$emit('page', meta.last_page)", tabindex='-1') {{ meta.last_page }}
            li.page-item(:class="{ 'disabled': !links.next }")
                a.page-link(@click.prevent="$emit('page', meta.current_page + 1)", :disabled='!links.next', tabindex='-1')
                    i.fa.fa-angle-right

        small.text-muted(v-if="meta") Showing {{ meta.from }} to {{ meta.to }} of {{ meta.total }} records
</template>

<style lang="scss" scoped>
.page-item.active {
  a.page-link {
    z-index: initial;
    &:hover {
      color: white;
    }
  }
}
</style>


<script>
export default {
  props: {
    meta: { required: true },
    links: { required: true },
    spacing: { default: 3 }
  },
  watch: {
    meta(val) {
      this.meta = val || {};
    },
    links(val) {
      this.links = val || {};
    }
  },
  computed: {
    last_page() {
      return (this.meta && this.meta.last_page) || 1;
    },
    current_page() {
      return (this.meta && this.meta.current_page) || 1;
    },
    max_pages() {
      return 2 * this.spacing + 1;
    },
    show_first_ellipsis() {
      return this.meta.current_page > this.spacing + 2;
    },
    show_last_ellipsis() {
      return this.meta.current_page + 1 < this.meta.last_page - this.spacing;
    },
    show_first_page_links() {
      return (
        this.meta.current_page > this.spacing + 1 && !this.pages.includes(1)
      );
    },
    show_last_page_links() {
      return (
        this.meta.current_page < this.meta.last_page - this.spacing &&
        !this.pages.includes(this.meta.last_page)
      );
    },
    offset() {
      const page_end = this.current_page + this.spacing;
      return this.last_page < page_end ? this.last_page - page_end : 0;
    },
    pages() {
      let negatives = 0;
      const pageList = [];
      const page_start = this.current_page + (this.offset - this.spacing);
      for (let page = page_start; page <= this.last_page; page++) {
        if (page <= 0) {
          negatives++;
          continue;
        }
        const maxPage = this.current_page + this.spacing;
        if (page <= maxPage) {
          pageList.push(page);
          continue;
        }
        if (pageList.length < this.max_pages) {
          pageList.push(page);
          negatives--;
        }
      }
      return pageList;
    }
  },
  methods: {
    current(page) {
      return page === this.meta.current_page;
    },
    onClick(page) {
      this.$emit("page", page);
    }
  }
};
</script>
