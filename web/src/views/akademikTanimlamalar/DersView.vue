<template>
  <div class="panel-page-content">
    <div class="content-header">
      <h1>Dersler</h1>

      <div class="buttons">
        <div class="button" @click="toggleAddLessonPopup">
          <i class="fa-solid fa-plus"></i>
          Ders Ekle
        </div>
      </div>
    </div>

    <div class="table">
      <div class="table-row table-header">
        <div class="table-cell">Ders Kodu</div>
        <div class="table-cell">Ders Adı</div>
        <div class="table-cell">Kontrol</div>
      </div>

      <div class="table-row" v-for="(item, index) in lessons" :key="index">
        <div class="table-cell">
          {{ item.lesson_code }}
        </div>
        <div class="table-cell">
          {{ item.lesson_subject }}
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
            @click="deleteLesson(item.id)"
          >
            <i class="fa-solid fa-trash"></i>
          </div>
        </div>
      </div>

      <div class="table-empty" v-if="lessons.length == 0">
        <p>Kayıt bulunamadı</p>
      </div>
    </div>
    <div class="table-information">
      <p>Toplam {{ lessons.length }} kayıt var</p>
    </div>

    <div class="popup" v-if="addLessonPopup">
      <div class="popup-content">
        <div class="popup-header">
          <h2>Ders Ekle</h2>

          <div class="close-button" @click="toggleAddLessonPopup">
            <i class="fa-solid fa-xmark"></i>
          </div>
        </div>

        <div class="row">
          <span>Ders Kodu</span>
          <input
            type="text"
            placeholder="Ders Kodu"
            v-model="addLessonPopupItem.lesson_code"
          />
        </div>

        <div class="row">
          <span>Ders Adı</span>
          <input
            type="text"
            placeholder="Ders Adı"
            v-model="addLessonPopupItem.lesson_subject"
          />
        </div>

        <div class="button-row" @click="addLesson">
          <div class="button">Ekle</div>
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
          <span>Ders Kodu</span>
          <input
            type="text"
            placeholder="Ders Kodu"
            v-model="editPopupItem.lesson_code"
          />
        </div>

        <div class="row">
          <span>Ders Adı</span>
          <input
            type="text"
            placeholder="Ders Adı"
            v-model="editPopupItem.lesson_subject"
          />
        </div>

        <div class="button-row" @click="editLesson(editPopupItem.id)">
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
  name: "DersView",
  data() {
    return {
      userId: "",
      lessons: [],
      addLessonPopup: false,
      addLessonPopupItem: {
        lesson_code: "",
        lesson_subject: "",
      },
      editPopup: false,
      editPopupItem: {
        lesson_code: "",
        lesson_subject: "",
      },
    };
  },
  async mounted() {
    document.title = "Dersler | Mufy";

    this.userId = this.$store.getters.getUser.id
      ? this.$store.getters.getUser.id
      : cookies.get("user-id");

    await this.getLessons();
  },
  methods: {
    async getLessons() {
      const result = await ApiService.getLessons(this.userId);
      if (!result.success) {
        return;
      }

      this.lessons = result.lessons;
    },
    toggleAddLessonPopup() {
      this.addLessonPopup = !this.addLessonPopup;
    },
    async addLesson() {
      const result = await ApiService.addLesson(
        this.userId,
        this.addLessonPopupItem
      );

      if (!result.success) {
        return;
      }

      await this.getLessons();
      this.toggleAddLessonPopup();
    },
    toggleEditPopup(item) {
      this.editPopup = !this.editPopup;
      this.editPopupItem = item;
    },
    async editLesson(id) {
      const result = await ApiService.updateLesson(this.userId, {
        id,
        ...this.editPopupItem,
      });

      if (!result.success) {
        return;
      }

      await this.getLessons();
      this.toggleEditPopup();
    },
    async deleteLesson(id) {
      const result = await ApiService.deleteLessonItem(this.userId, id);

      if (!result.success) {
        return;
      }

      await this.getLessons();
    },
  },
};
</script>

<style scoped>
@import url("../../assets/style/panel-pages.css");

.table-row {
  grid-template-columns: repeat(3, 1fr);
}
</style>
