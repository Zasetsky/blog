<script setup lang="ts">
import { defineProps } from "vue";
defineProps({ pagination: Object });
</script>

<template>
  <el-pagination
    background
    layout="prev, pager, next"
    :current-page="pagination.current_page"
    :page-size="10"
    :total="pagination.total"
    @current-change="$emit('change-page', $event)"
  />
</template>
