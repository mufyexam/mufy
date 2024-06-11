<template>
  <div class="panel-page-content">
    <div class="content-header">
      <h1>Sınavlar</h1>

      <div class="buttons">
        <div class="button" @click="toggleAddExamPopup">
          <i class="fa-solid fa-plus"></i>
          Sınav Oluştur
        </div>
      </div>
    </div>

    <div class="table">
      <div class="table-row table-header">
        <div class="table-cell">Sınav Adı</div>
        <div class="table-cell">Sınav Tarihi</div>
        <div class="table-cell">Sınav Durumu</div>
        <div class="table-cell">Sınav Dersleri</div>
        <div class="table-cell">Kontrol</div>
      </div>

      <div class="table-row" v-for="(item, index) in exams" :key="index">
        <div class="table-cell">
          {{ item.exam_name }}
        </div>
        <div class="table-cell">
          {{ item.exam_date }}
        </div>
        <div class="table-cell">
          <span :class="item.exam_status == 1 ? 'text-green' : 'text-success'">
            {{ item.exam_status == 1 ? "Bekleniyor" : "Oluşturuldu" }}
          </span>
        </div>
        <div class="table-cell table-array">
          <span v-for="(l, i) in item.exam_lessons" :key="i">
            •{{ l.lesson_subject }}</span
          >
        </div>
        <div class="table-cell table-buttons">
          <div
            class="control-button edit-button"
            @click="toggleEditPopup(item)"
          >
            <i class="fa-solid fa-pen-to-square"></i>
          </div>

          <div
            class="control-button delete-button"
            @click="deleteExam(item.id)"
          >
            <i class="fa-solid fa-trash"></i>
          </div>
          <div
            class="control-button start-button"
            @click="startExam(item.id)"
            v-if="item.exam_status == 1"
          >
            <i class="fa-solid fa-play"></i>
          </div>
        </div>
      </div>

      <div class="table-empty" v-if="exams.length == 0">
        <p>Kayıt bulunamadı</p>
      </div>
    </div>
    <div class="table-information">
      <p>Toplam {{ exams.length }} kayıt var</p>
    </div>

    <div class="popup" v-if="addExamPopup">
      <div class="popup-content">
        <div class="popup-header">
          <h2>Sınav Oluştur</h2>

          <div class="close-button" @click="toggleAddExamPopup">
            <i class="fa-solid fa-xmark"></i>
          </div>
        </div>

        <div class="row">
          <span>Sınav Adı</span>
          <input
            type="text"
            v-model="addExamPopupItem.exam_name"
            placeholder="Sınav Adı"
          />
        </div>

        <div class="row">
          <span>Sınav Tarihi</span>
          <input
            type="date"
            v-model="addExamPopupItem.exam_day"
            placeholder="Sınav Tarihi"
          />
        </div>

        <div class="row">
          <span>Saat Giriniz</span>
          <div class="hour-inputs">
            <input
              type="number"
              v-model="addExamPopupItem.exam_hour"
              placeholder="Saat"
              max="23"
              min="0"
            />
            <input
              type="number"
              v-model="addExamPopupItem.exam_minute"
              placeholder="Dakika"
              max="59"
              min="0"
            />
          </div>
        </div>

        <div class="row">
          <span style="display: block; margin-bottom: 10px"
            >Sınav Dersleri</span
          >
          <div v-for="(l, i) in lessons" :key="i">
            <input
              type="checkbox"
              :name="l.lesson_code"
              :id="l.lesson_code"
              v-model="l.checked"
            />
            <label :for="l.lesson_code">{{ l.lesson_subject }}</label>
          </div>
        </div>

        <div class="button-row" @click="addExam">
          <div class="button">Oluştur</div>
        </div>
      </div>
    </div>

    <div class="popup" v-if="editPopup">
      <div class="popup-content">
        <div class="popup-header">
          <h2>Düzenle</h2>

          <div class="close-button" @click="toggleEditPopup">
            <i class="fa-solid fa-xmark"></i>
          </div>
        </div>

        <div class="row">
          <span>Sınav Adı</span>
          <input
            type="text"
            v-model="editPopupItem.exam_name"
            placeholder="Sınav Adı"
          />
        </div>

        <div class="row">
          <span>Sınav Tarihi</span>
          <input
            type="date"
            v-model="editPopupItem.exam_day"
            placeholder="Sınav Tarihi"
          />
        </div>

        <div class="row">
          <span>Saat Giriniz</span>
          <div class="hour-inputs">
            <input
              type="number"
              v-model="editPopupItem.exam_hour"
              placeholder="Saat"
              max="23"
              min="0"
            />
            <input
              type="number"
              v-model="editPopupItem.exam_minute"
              placeholder="Dakika"
              max="59"
              min="0"
            />
          </div>
        </div>

        <div class="row">
          <span style="display: block; margin-bottom: 10px"
            >Sınav Dersleri</span
          >
          <div v-for="(l, i) in lessons" :key="i">
            <input
              type="checkbox"
              :name="l.lesson_code"
              :id="l.lesson_code"
              v-model="l.checked"
            />
            <label :for="l.lesson_code">{{ l.lesson_subject }}</label>
          </div>
        </div>

        <div class="button-row" @click="editExam(editPopupItem.id)">
          <div class="button">Kaydet</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ApiService from "@/services/api-service";
import cookies from "js-cookie";

export default {
  name: "SinavOlusturView",
  data() {
    return {
      userId: "",
      exams: [],
      lessons: [],
      addExamPopup: false,
      addExamPopupItem: {
        exam_name: "",
        exam_day: "",
        exam_date: "",
        exam_hour: "",
        exam_minute: "",
        exam_status: "",
        exam_lessons: [],
      },
      editPopup: false,
      editPopupItem: {
        exam_name: "",
        exam_day: "",
        exam_date: "",
        exam_hour: "",
        exam_minute: "",
        exam_status: "",
        exam_lessons: [],
      },
    };
  },
  async mounted() {
    document.title = "Sınav Oluştur | Mufy";

    this.userId = this.$store.getters.getUser.id
      ? this.$store.getters.getUser.id
      : cookies.get("user-id");

    await this.getExams();
    await this.getLessons();
  },
  methods: {
    toggleAddExamPopup() {
      if (this.addExamPopup == true) {
        this.lessons.forEach((l) => {
          l.checked = false;
        });
      }

      this.addExamPopup = !this.addExamPopup;
    },
    async getExams() {
      const result = await ApiService.getExams(this.userId);
      if (!result.success) {
        return;
      }

      this.exams = result.exams;
    },
    async getLessons() {
      const result = await ApiService.getLessons(this.userId);
      if (!result.success) {
        return;
      }

      this.lessons = result.lessons;
    },
    toggleEditPopup(item = null) {
      if (this.editPopup == false && item) {
        this.editPopupItem = item;
        this.editPopupItem.exam_day = item.exam_date.split(" ")[0];
        this.editPopupItem.exam_hour = item.exam_date
          .split(" ")[1]
          .split(":")[0];
        this.editPopupItem.exam_minute = item.exam_date
          .split(" ")[1]
          .split(":")[1];

        this.lessons.forEach((l) => {
          this.editPopupItem.exam_lessons.forEach((el) => {
            if (l.lesson_code == el.lesson_code) {
              l.checked = true;
            }
          });
        });
      } else if (this.editPopup == true) {
        this.editPopupItem = {
          exam_name: "",
          exam_day: "",
          exam_date: "",
          exam_hour: "",
          exam_minute: "",
          exam_status: "",
          exam_lessons: [],
        };

        this.lessons.forEach((l) => {
          l.checked = false;
        });
      }
      this.editPopup = !this.editPopup;
    },
    async addExam() {
      let todaysDate = new Date();

      todaysDate.setDate(todaysDate.getDate() - 1);

      if (new Date(this.addExamPopupItem.exam_day) < todaysDate) {
        alert("Sınav tarihi bugünün tarihinden eski olamaz.");
        return;
      }

      if (
        Number(this.addExamPopupItem.exam_hour) > 23 ||
        Number(this.addExamPopupItem.exam_hour) < 0
      ) {
        alert("Saat 0-23 arasında olmalıdır.");
        return;
      }

      if (
        this.addExamPopupItem.exam_minute > 59 ||
        this.addExamPopupItem.exam_minute < 0
      ) {
        alert("Dakika 0-59 arasında olmalıdır.");
        return;
      }

      this.addExamPopupItem.exam_lessons = this.lessons.filter(
        (l) => l.checked
      );
      this.addExamPopupItem.exam_date = `${this.addExamPopupItem.exam_day} ${
        this.addExamPopupItem.exam_hour == 0
          ? "00"
          : this.addExamPopupItem.exam_hour
      }:${
        this.addExamPopupItem.exam_minute == 0
          ? "00"
          : this.addExamPopupItem.exam_minute
      }:00`;

      const result = await ApiService.addExam(
        this.userId,
        this.addExamPopupItem
      );
      if (!result.success) {
        return;
      }

      this.toggleAddExamPopup();
      this.addExamPopupItem = {
        exam_name: "",
        exam_day: "",
        exam_date: "",
        exam_hour: "",
        exam_minute: "",
        exam_status: "",
        exam_lessons: [],
      };
      await this.getLessons();
      await this.getExams();
    },
    async editExam(id) {
      this.editPopupItem.exam_lessons = this.lessons.filter((l) => l.checked);
      this.editPopupItem.exam_date = `${this.editPopupItem.exam_day} ${
        this.editPopupItem.exam_hour == 0 ? "00" : this.editPopupItem.exam_hour
      }:${
        this.editPopupItem.exam_minute == 0
          ? "00"
          : this.editPopupItem.exam_minute
      }:00`;

      const result = await ApiService.updateExam(this.userId, {
        id,
        ...this.editPopupItem,
      });

      if (!result.success) {
        return;
      }

      this.toggleEditPopup();
      this.editPopupItem = {
        exam_name: "",
        exam_day: "",
        exam_date: "",
        exam_hour: "",
        exam_minute: "",
        exam_status: "",
        exam_lessons: [],
      };
      this.lessons.forEach((l) => {
        l.checked = false;
      });
    },
    async deleteExam(id) {
      const result = await ApiService.deleteExamItem(this.userId, id);
      if (!result.success) {
        return;
      }

      await this.getExams();
    },
    startExam(id) {
      this.$router.push(`/panel/sinav/sinav-baslat/${id}`);
    },
  },
};
</script>

<style scoped>
@import url("../../assets/style/panel-pages.css");
</style>
