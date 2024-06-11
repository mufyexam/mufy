<template>
  <div class="panel-page-content">
    <div class="content-header">
      <h1>Personeller</h1>

      <div class="buttons">
        <div class="button" @click="toggleAddTeacherPopup">
          <i class="fa-solid fa-plus"></i>
          Personel Ekle
        </div>
      </div>
    </div>

    <div class="table">
      <div class="table-row table-header">
        <div class="table-cell">TC No</div>
        <div class="table-cell">E-mail</div>
        <div class="table-cell">Ad</div>
        <div class="table-cell">Soyad</div>
        <div class="table-cell">Kontrol</div>
      </div>

      <div class="table-row" v-for="(item, index) in teachers" :key="index">
        <div class="table-cell">
          {{ item.tcno }}
        </div>
        <div class="table-cell">
          {{ item.email }}
        </div>
        <div class="table-cell">
          {{ item.name }}
        </div>
        <div class="table-cell">
          {{ item.surname }}
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
            @click="deleteTeacher(item.id)"
          >
            <i class="fa-solid fa-trash"></i>
          </div>
        </div>
      </div>

      <div class="table-empty" v-if="teachers.length == 0">
        <p>Kayıt bulunamadı</p>
      </div>
    </div>
    <div class="table-information">
      <p>Toplam {{ teachers.length }} kayıt var</p>
    </div>

    <div class="popup" v-if="addTeacherPopup">
      <div class="popup-content">
        <div class="popup-header">
          <h2>Personel Ekle</h2>

          <div class="close-button" @click="toggleAddTeacherPopup">
            <i class="fa-solid fa-xmark"></i>
          </div>
        </div>

        <div class="row">
          <span>TC No</span>
          <input
            type="number"
            v-model="addTeacherPopupItem.tcno"
            placeholder="TC No"
          />
        </div>

        <div class="row">
          <span>Ad</span>
          <input
            type="text"
            v-model="addTeacherPopupItem.name"
            placeholder="Ad"
          />
        </div>

        <div class="row">
          <span>Soyad</span>
          <input
            type="text"
            v-model="addTeacherPopupItem.surname"
            placeholder="Soyad"
          />
        </div>

        <div class="row">
          <span>E-mail</span>
          <input
            type="email"
            v-model="addTeacherPopupItem.email"
            placeholder="E-mail"
          />
        </div>

        <div class="row">
          <span>Şifre</span>
          <input
            type="text"
            v-model="addTeacherPopupItem.password"
            placeholder="Şifre"
          />
        </div>

        <div class="button-row" @click="addTeacher">
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
          <span>TC No</span>
          <input
            type="number"
            v-model="editPopupItem.tcno"
            placeholder="TC No"
          />
        </div>

        <div class="row">
          <span>Ad</span>
          <input type="text" v-model="editPopupItem.name" placeholder="Ad" />
        </div>

        <div class="row">
          <span>Soyad</span>
          <input
            type="text"
            v-model="editPopupItem.surname"
            placeholder="Soyad"
          />
        </div>

        <div class="row">
          <span>E-mail</span>
          <input
            type="email"
            v-model="editPopupItem.email"
            placeholder="E-mail"
          />
        </div>

        <div class="row">
          <span>Şifre</span>
          <input
            type="text"
            v-model="editPopupItem.password"
            placeholder="Şifre"
          />
        </div>

        <div class="button-row" @click="editTeacher">
          <div class="button">Düzenle</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ApiService from "@/services/api-service";
import cookies from "js-cookie";

export default {
  name: "PersonelView",
  data() {
    return {
      userId: "",
      teachers: [],
      addTeacherPopup: false,
      addTeacherPopupItem: {
        tcno: "",
        name: "",
        surname: "",
        email: "",
        role: "teacher",
      },
      editPopup: false,
      editPopupItem: {
        tcno: "",
        name: "",
        surname: "",
        email: "",
        password: "",
        role: "teacher",
      },
    };
  },
  async mounted() {
    document.title = "Personeller | Mufy";

    this.userId = this.$store.getters.getUser.id
      ? this.$store.getters.getUser.id
      : cookies.get("user-id");

    await this.getTeachers();
  },
  methods: {
    async getTeachers() {
      const result = await ApiService.getUsers(this.userId, "teacher");
      if (!result.success) {
        return;
      }

      this.teachers = result.users;
    },
    toggleAddTeacherPopup() {
      this.addTeacherPopup = !this.addTeacherPopup;
    },
    async addTeacher() {
      const result = await ApiService.addUser(
        this.userId,
        this.addTeacherPopupItem
      );
      if (!result.success) {
        return;
      }

      this.addTeacherPopupItem = {
        tcno: "",
        name: "",
        surname: "",
        email: "",
        role: "teacher",
      };

      this.toggleAddTeacherPopup();
      await this.getTeachers();
    },
    async deleteTeacher(id) {
      const result = await ApiService.deleteUserItem(this.userId, id);
      if (!result.success) {
        return;
      }

      await this.getTeachers();
    },
    toggleEditPopup(item) {
      this.editPopup = !this.editPopup;
      this.editPopupItem = item;
    },
    async editTeacher(id) {
      const result = await ApiService.updateUser(this.userId, {
        id,
        ...this.editPopupItem,
      });
      if (!result.success) {
        return;
      }

      this.toggleEditPopup();
      await this.getTeachers();
    },
  },
};
</script>

<style scoped>
@import url("../../assets/style/panel-pages.css");
</style>
