<script setup lang="ts">
import { Pagination } from "@/stores/articles/types/IArticlesStore";
import { defineProps, defineEmits } from "vue";

defineProps<{
  pagination: Pagination;
}>();

const emit = defineEmits<{
  updatePage: [number];
}>();

const handlePageChange = (page: number) => {
  emit("updatePage", page);
};
</script>

<template>
  <el-pagination
    :current-page="pagination.current_page"
    :page-size="pagination.per_page"
    :total="pagination.total_items"
    background
    layout="prev, pager, next"
    @current-change="handlePageChange"
  />
</template>
