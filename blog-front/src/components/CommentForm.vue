<script setup lang="ts">
import { ref, defineProps } from "vue";
import { ElMessage } from "element-plus";

const props = defineProps<{
  articleId: string;
  addComment: (
    articleId: string,
    subject: string,
    body: string
  ) => Promise<void>;
}>();

const subject = ref("");
const message = ref("");

const isSubmitting = ref(false);
const isSubmitted = ref(false);

// Обработчик отправки комментария
const handleSubmit = async () => {
  isSubmitting.value = true;

  try {
    await props.addComment(props.articleId, subject.value, message.value);
    isSubmitted.value = true;
    ElMessage.success("Ваше сообщение успешно отправлено!");
    subject.value = "";
    message.value = "";
  } catch (error) {
    ElMessage.error("Ошибка отправки сообщения. Попробуйте ещё раз.");
  } finally {
    isSubmitting.value = false;
  }
};
</script>

<template>
  <div class="comment-form">
    <div v-if="isSubmitted" class="comment-form__success">
      <el-card class="comment-form__success-card">
        <p>Ваше сообщение успешно отправлено!</p>
      </el-card>
    </div>

    <div v-else>
      <el-form :model="{ subject, message }" class="comment-form__form">
        <el-form-item>
          <el-input v-model="subject" placeholder="Тема сообщения" clearable />
        </el-form-item>
        <el-form-item>
          <el-input
            v-model="message"
            placeholder="Текст сообщения"
            type="textarea"
            rows="4"
            clearable
          />
        </el-form-item>
        <el-form-item>
          <el-button
            type="primary"
            @click="handleSubmit"
            :disabled="isSubmitting"
            :loading="isSubmitting"
          >
            Отправить
          </el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<style scoped>
.comment-form {
  &__form {
    max-width: 600px;
    margin: 0 auto;
  }

  &__success-card {
    text-align: center;
    font-size: 16px;
    color: #2c7a7b;
    background-color: #f0fdfa;
  }
}
</style>
