<template>
  <div class="panel-page-content">
    <div class="content-header">
      <h1>Sınavı Başlat</h1>
    </div>

    <div class="exam-informations">
      <div class="sheet">
        <div class="row">
          <div class="field-name">
            <span>Sınav Adı :</span>
          </div>
          <div class="field-value">
            <span>{{ exam.exam_name }}</span>
          </div>
        </div>

        <div class="row">
          <div class="field-name">
            <span>Sınav Tarihi :</span>
          </div>
          <div class="field-value">
            <span>{{ exam.exam_date }}</span>
          </div>
        </div>

        <div class="row">
          <div class="field-name">
            <span>Sınav Dersleri :</span>
          </div>
          <div class="field-value lessons">
            <span
              class="lesson"
              v-for="(lesson, index) in exam.exam_lessons"
              :key="index"
              >{{ lesson.lesson_subject }}</span
            >
          </div>
        </div>

        <div class="row">
          <div class="field-name">
            <span>Sınava Girecek Öğrenci Sayısı :</span>
          </div>
          <div class="field-value">
            <span>{{ studentsCount }}</span>
          </div>
        </div>
      </div>

      <div class="sheet">
        <div class="sheet-header">
          <div>
            <h3>Derslik Seçin</h3>
          </div>
          <div>
            <h3>
              Seçilen Kapasite
              <span
                :class="
                  totalRoomCapacity < studentsCount
                    ? 'text-error'
                    : 'text-success'
                "
                >{{ totalRoomCapacity }}/{{ studentsCount }}</span
              >
            </h3>
          </div>
        </div>

        <table border="1">
          <tr>
            <th></th>
            <th>Kurum</th>
            <th>Kampüs</th>
            <th>Bina</th>
            <th>Kat</th>
            <th>Salon Adı</th>
            <th>Kapasite</th>
          </tr>

          <tr
            v-for="(room, index) in activeRooms"
            :key="index"
            :class="room.checked ? 'checked' : ''"
          >
            <td>
              <input
                type="checkbox"
                v-model="room.checked"
                @change="calculateCapacity(room)"
              />
            </td>
            <td>{{ room.inst_name }}</td>
            <td>{{ room.campus_name }}</td>
            <td>{{ room.building_name }}</td>
            <td>{{ room.floor_name }}</td>
            <td>{{ room.room_name }}</td>
            <td>{{ room.capacity }}</td>
          </tr>
        </table>
      </div>

      <div class="sheet">
        <div class="sheet-header">
          <div>
            <h3>Gözetmen Personel Seçin</h3>
          </div>
          <div>
            <h3>
              Seçilen Kapasite
              <span
                :class="
                  totalSelectedTeacherCount < totalSelectedRoomCount
                    ? 'text-error'
                    : 'text-success'
                "
                >{{ totalSelectedTeacherCount }}/{{
                  totalSelectedRoomCount
                }}</span
              >
            </h3>
          </div>
        </div>

        <table border="1">
          <tr>
            <th></th>
            <th>TC No</th>
            <th>E-mail</th>
            <th>Ad</th>
            <th>Soyad</th>
          </tr>

          <tr
            v-for="(teacher, index) in teachers"
            :key="index"
            :class="teacher.checked ? 'checked' : ''"
          >
            <td>
              <input
                type="checkbox"
                v-model="teacher.checked"
                @change="calculateTeacherCount(teacher)"
              />
            </td>
            <td>{{ teacher.tcno }}</td>
            <td>{{ teacher.email }}</td>
            <td>{{ teacher.name }}</td>
            <td>{{ teacher.surname }}</td>
          </tr>
        </table>
      </div>

      <div class="content-header">
        <div></div>
        <div>
          <div class="button-row" @click="startExam">
            <div class="button">Sınavı Başlat</div>
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
  name: "SinavBaslatView",
  data() {
    return {
      userId: "",
      examId: "",
      studentsCount: 0,
      exam: {
        exam_name: "",
        exam_date: "",
        exam_lessons: [],
      },
      teachers: [],
      activeRooms: [],
      totalRoomCapacity: 0,
      totalSelectedRoomCount: 0,
      totalSelectedTeacherCount: 0,
    };
  },
  async mounted() {
    document.title = "Sınavı Başlat | Mufy";

    this.userId = this.$store.getters.getUser.id
      ? this.$store.getters.getUser.id
      : cookies.get("user-id");

    this.examId = this.$route.params.examId;
    await this.getExamInformations();
  },
  methods: {
    async getExamInformations() {
      const result = await ApiService.getExamInformations(
        this.userId,
        this.examId
      );
      if (!result.success) {
        return;
      }

      this.exam = result.exam[0];
      this.studentsCount = result.students_count;
      this.teachers = result.teachers;
      this.activeRooms = result.active_rooms;
    },
    calculateCapacity() {
      this.totalRoomCapacity = 0;
      this.totalSelectedRoomCount = 0;

      this.activeRooms.forEach((room) => {
        if (room.checked) {
          this.totalRoomCapacity += Number(room.capacity);
          this.totalSelectedRoomCount++;
        }
      });
    },
    calculateTeacherCount() {
      this.totalSelectedTeacherCount = 0;

      this.teachers.forEach((teacher) => {
        if (teacher.checked) {
          this.totalSelectedTeacherCount++;
        }
      });
    },
    async startExam() {
      const selectedRooms = this.activeRooms.filter((room) => room.checked);
      const selectedTeachers = this.teachers.filter(
        (teacher) => teacher.checked
      );

      if (this.totalRoomCapacity < this.studentsCount) {
        alert("Seçilen dersliklerin kapasitesi yetersiz.");
        return;
      }

      if (this.totalSelectedTeacherCount < this.totalSelectedRoomCount) {
        alert("Seçilen gözetmen personel sayısı yetersiz.");
        return;
      }

      if (this.totalSelectedTeacherCount > selectedRooms.length) {
        alert("Seçilen gözetmen personel sayısı fazla.");
        return;
      }

      const result = await ApiService.startExam(this.userId, {
        exam: this.exam,
        selectedRooms,
        selectedTeachers,
      });

      if (!result.success) {
        return;
      }

      this.$router.push("/panel/sinav/sinav-olustur");
    },
  },
};
</script>

<style scoped>
@import url("../../assets/style/panel-pages.css");

.exam-informations {
  margin-top: 20px;
}

.sheet {
  width: 100%;
  padding: 10px;
  background-color: #fff;
  border-radius: 5px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  margin-block: 20px;
}

.sheet .row {
  width: 100%;
  display: grid;
  grid-template-columns: 150px 1fr;
  column-gap: 10px;
  margin-block: 10px;
}

.sheet .row .field-name {
  font-weight: 600;
  color: #333;
  display: flex;
  align-items: center;
  justify-content: flex-end;
  text-align: right;
}

.sheet .row .field-value {
  display: flex;
  align-items: center;
}

.sheet .lessons {
  display: flex;
  gap: 10px;
}

.sheet .lessons .lesson {
  padding: 7px 10px;
  border-radius: 5px;
  background-color: var(--color-4);
  color: white;
}

.sheet .sheet-header {
  display: flex;
  width: 100%;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 10px;
}

.sheet table {
  width: 100%;
  transition: all 0.3s ease;
}

.sheet table th {
  text-align: start;
}

.sheet table tr:nth-child(odd) {
  background-color: #e6e6e6;
}

.sheet table tr:nth-child(1) {
  background-color: var(--color-4);
  color: white;
}

.sheet table tr td,
.sheet table tr th {
  padding: 5px;
}

.sheet table tr.checked {
  background-color: green;
  color: white;
}

.text-error {
  color: red;
}
</style>
