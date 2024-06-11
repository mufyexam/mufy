<template>
  <div class="raporlar">
    <div class="page-header">
      <h1>Mufy Sınav Sistemine Hoşgeldiniz</h1>
    </div>

    <div class="sheet">
      <div class="sheet-header">
        <h2>Sınavlarınız</h2>
      </div>

      <div class="exam-item" v-for="(exam, index) in userExams" :key="index">
        <div class="row">
          <div class="field-name">
            <span>Sınav Adı:</span>
          </div>
          <div class="field-value">
            <span>{{ exam.exam_name }}</span>
          </div>
        </div>

        <div class="row">
          <div class="field-name">
            <span>Sınav Tarihi:</span>
          </div>
          <div class="field-value">
            <span>{{ exam.exam_date }}</span>
          </div>
        </div>

        <div class="row">
          <div class="field-name">
            <span>Sınav Dersleri:</span>
          </div>
          <div class="field-value">
            <span
              class="exam-lesson"
              v-for="(lesson, j) in exam.exam_lessons"
              :key="j"
              >{{
                `${lesson.lesson_subject}${
                  j != exam.exam_lessons.length - 1 ? ", " : ""
                }`
              }}</span
            >
          </div>
        </div>

        <div class="row">
          <div class="field-name">
            <span>Giriş Belgesi:</span>
          </div>
          <div class="field-value">
            <span class="field-button" @click="seeExamPaper(exam.exam_id)"
              >Belgeyi Gör</span
            >
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ApiService from "@/services/api-service";
import cookies from "js-cookie";

export default {
  name: "RaporlarView",
  data() {
    return {
      userId: "",
      userExams: [],
    };
  },
  async mounted() {
    document.title = "Sınavlarım | Mufy";

    this.userId = this.$store.getters.getUser.id
      ? this.$store.getters.getUser.id
      : cookies.get("user-id");
    await this.getUserExams();
  },
  methods: {
    async getUserExams() {
      const result = await ApiService.getUserExams(this.userId);
      if (!result.success) {
        return;
      }

      this.userExams = result.user_exams;
    },
    seeExamPaper(examId) {
      this.$router.push(`/rapor/sinav-belgesi/${examId}`);
    },
  },
};
</script>

<style scoped>
.raporlar {
  width: 100%;
  margin-top: 20px;
  padding-inline: 20px;
}

.raporlar .page-header {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 20px;
}

.sheet {
  width: 100%;
  height: 100%;
  background-color: white;
  padding: 20px;
  border-radius: 5px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.sheet .sheet-header {
  text-align: center;
  margin-bottom: 20px;
}

.exam-item {
  width: 100%;
  background-color: var(--color-6);
  padding: 20px;
  border-radius: 5px;
  margin-block: 20px;
  display: flex;
  flex-direction: column;
  row-gap: 10px;
}

.exam-item .row {
  display: grid;
  grid-template-columns: 150px 1fr;
  column-gap: 10px;
}

.exam-item span {
  font-size: 20px;
}

.exam-item .field-name {
  text-align: right;
}

.exam-item .field-button {
  padding: 5px 10px;
  background-color: var(--color-4);
  color: white;
  border-radius: 5px;
  cursor: pointer;
}

@media screen and (max-width: 470px) {
  .exam-item span {
    font-size: 15px;
  }

  .exam-item .row {
    grid-template-columns: 100px 1fr;
  }

  .raporlar .page-header h1 {
    font-size: 20px;
    text-align: center;
  }
}

@media screen and (max-width: 330px) {
  .exam-item {
    padding: 10px;
  }

  .exam-item .row {
    grid-template-columns: 50px 1fr;
  }
}
</style>
