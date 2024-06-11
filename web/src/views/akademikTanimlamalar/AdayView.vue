<template>
  <div class="panel-page-content">
    <div class="content-header">
      <h1>Adaylar</h1>

      <div class="buttons">
        <div class="button" @click="toggleAddStudentPopup">
          <i class="fa-solid fa-plus"></i>
          Aday Ekle
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

      <div class="table-row" v-for="(item, index) in students" :key="index">
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
            @click="deleteStudent(item.id)"
          >
            <i class="fa-solid fa-trash"></i>
          </div>
        </div>
      </div>

      <div class="table-empty" v-if="students.length == 0">
        <p>Kayıt bulunamadı</p>
      </div>
    </div>
    <div class="table-information">
      <p>Toplam {{ students.length }} kayıt var</p>
    </div>

    <div class="popup" v-if="addStudentPopup">
      <div class="popup-content">
        <div class="popup-header">
          <h2>Aday Ekle</h2>

          <div class="close-button" @click="toggleAddStudentPopup">
            <i class="fa-solid fa-xmark"></i>
          </div>
        </div>

        <div class="row">
          <span>TC No</span>
          <input
            type="number"
            v-model="addStudentPopupItem.tcno"
            placeholder="TC No"
          />
        </div>

        <div class="row">
          <span>Ad</span>
          <input
            type="text"
            v-model="addStudentPopupItem.name"
            placeholder="Ad"
          />
        </div>

        <div class="row">
          <span>Soyad</span>
          <input
            type="text"
            v-model="addStudentPopupItem.surname"
            placeholder="Soyad"
          />
        </div>

        <div class="row">
          <span>E-mail</span>
          <input
            type="email"
            v-model="addStudentPopupItem.email"
            placeholder="E-mail"
          />
        </div>

        <div class="row">
          <span>Şifre</span>
          <input
            type="text"
            v-model="addStudentPopupItem.password"
            placeholder="Şifre"
          />
        </div>

        <div class="row" v-for="(lesson, i) in lessons" :key="i">
          <input
            type="checkbox"
            :name="lesson.lesson_code"
            :id="lesson.lesson_code"
            v-model="lesson.checked"
            @change="toggleLessonSelection(lesson)"
          />
          <label :for="lesson.lesson_code">{{ lesson.lesson_subject }}</label>
        </div>

        <div class="button-row" @click="addStudent">
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

        <div class="row" v-for="(lesson, i) in lessons" :key="i">
          <input
            type="checkbox"
            :name="lesson.lesson_code"
            :id="lesson.lesson_code"
            v-model="lesson.checked"
          />
          <label :for="lesson.lesson_code">{{ lesson.lesson_subject }}</label>
        </div>

        <div class="button-row" @click="editStudent(editPopupItem.id)">
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
  name: "AdayView",
  data() {
    return {
      userId: "",
      students: [],
      lessons: [],
      addStudentPopup: false,
      addStudentPopupItem: {
        tcno: "",
        email: "",
        password: "",
        name: "",
        surname: "",
        role: "student",
        selectedLessons: [],
      },
      editPopup: false,
      editPopupItem: {
        tcno: "",
        email: "",
        password: "",
        name: "",
        surname: "",
        role: "student",
      },
    };
  },
  async mounted() {
    document.title = "Adaylar | Mufy";

    this.userId = this.$store.getters.getUser.id
      ? this.$store.getters.getUser.id
      : cookies.get("user-id");

    await this.getStudents();
    await this.getLessons();
  },
  methods: {
    async getStudents() {
      const result = await ApiService.getUsers(this.userId, "student");
      if (!result.success) {
        return;
      }

      this.students = result.users;
    },
    async getLessons() {
      const result = await ApiService.getLessons(this.userId);
      if (!result.success) {
        return;
      }

      this.lessons = result.lessons;
    },
    toggleLessonSelection(selectedLesson) {
      if (selectedLesson.checked) {
        this.addStudentPopupItem.selectedLessons.push(selectedLesson);
      } else {
        const index = this.addStudentPopupItem.selectedLessons.findIndex(
          (lesson) => lesson.id === selectedLesson.id
        );
        if (index !== -1) {
          this.addStudentPopupItem.selectedLessons.splice(index, 1);
        }
      }
    },
    toggleAddStudentPopup() {
      this.addStudentPopup = !this.addStudentPopup;
    },
    async addStudent() {
      const result = await ApiService.addUser(
        this.userId,
        this.addStudentPopupItem
      );
      if (!result.success) {
        return;
      }

      this.lessons.forEach((lesson, index) => {
        if (lesson.checked) {
          this.lessons[index].checked = false;
        }
      });
      this.addStudentPopupItem = {
        tcno: "",
        email: "",
        password: "",
        name: "",
        surname: "",
        role: "student",
        selectedLessons: [],
      };

      await this.getStudents();
      this.toggleAddStudentPopup();
    },
    toggleEditPopup(item) {
      if (this.editPopup == false) {
        item.lessons.forEach((selectedLesson) => {
          const lessonId = selectedLesson.lesson_id;
          this.lessons.forEach((lesson) => {
            if (lesson.id === lessonId) {
              lesson.checked = true;
            }
          });
        });
      } else {
        this.lessons.forEach((lesson, index) => {
          if (lesson.checked) {
            this.lessons[index].checked = false;
          }
        });
      }
      this.editPopup = !this.editPopup;
      this.editPopupItem = item;
    },
    async editStudent(id) {
      this.editPopupItem.lessons = [];
      this.lessons.forEach((lesson) => {
        if (lesson.checked) {
          this.editPopupItem.lessons.push({
            lesson_id: lesson.id,
          });
        }
      });

      const result = await ApiService.updateUser(this.userId, {
        id,
        ...this.editPopupItem,
      });
      if (!result.success) {
        return;
      }

      await this.getStudents();
      this.toggleEditPopup();

      this.lessons.forEach((lesson, index) => {
        if (lesson.checked) {
          this.lessons[index].checked = false;
        }
      });
    },
    async deleteStudent(id) {
      const result = await ApiService.deleteUserItem(this.userId, id);
      if (!result.success) {
        return;
      }

      await this.getStudents();
    },
  },
};
</script>

<style scoped>
@import url("../../assets/style/panel-pages.css");
</style>
