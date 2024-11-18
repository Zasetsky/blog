<script setup lang="ts">
import { defineProps } from "vue";
import { Comment } from "@/stores/comments/types/IComments";
import dayjs from "dayjs";

defineProps<{ comments: Comment[] }>();

// Функция для форматирования даты
const formatDate = (date: string) => dayjs(date).format("DD.MM.YYYY HH:mm");
</script>

<template>
  <div class="comments">
    <div v-if="comments.length > 0">
      <div class="comments__item" v-for="comment in comments" :key="comment.id">
        <div class="comments__message">
          <div class="comments__header">
            <span class="comments__subject">{{ comment.subject }}</span>
            <span class="comments__date">{{
              formatDate(comment.created_at)
            }}</span>
          </div>
          <p class="comments__body">{{ comment.body }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.comments {
  max-width: 600px;
  padding: 20px;
  border-radius: 10px;

  &__item {
    margin-bottom: 20px;
    display: flex;
  }

  &__message {
    position: relative;
    max-width: 80%;
    background-color: #fff;
    border-radius: 10px;
    padding: 15px 20px;
    color: #004d40;

    // Добавление стрелочки
    &::before {
      content: "";
      position: absolute;
      top: 10px;
      left: -15px;
      width: 0;
      height: 0;
      border-style: solid;
      border-width: 10px 15px 10px 0;
      border-color: transparent #fff transparent transparent;
    }
  }

  &__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    font-size: 14px;
  }

  &__subject {
    font-weight: bold;
    color: #00695c;
  }

  &__date {
    margin-left: 50px;
    font-size: 12px;
    color: #00796b;
  }

  &__body {
    font-size: 14px;
    line-height: 1.6;
    margin: 0;
  }

  // Анимация появления
  &__item {
    animation: fadeIn 0.3s ease-in-out;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
}
</style>
