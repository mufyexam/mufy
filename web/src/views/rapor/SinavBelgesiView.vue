<template>
  <div class="paper-page">
    <div class="control-bar">
      <div class="control-bar-button">
        <router-link to="/rapor">Sınav Listesine Geri Dön</router-link>
      </div>

      <div class="control-bar-button" @click="downloadPaper">
        <span>Belgeyi İndir <i class="fa-solid fa-file-arrow-down"></i></span>
      </div>
    </div>

    <div class="paper-container" id="exam-paper">
      <div class="mufy">
        <img src="./../../assets/img/mufy-logo.png" alt="logo" />
        <h3>Mufy Sınav Otomasyonu</h3>
      </div>
      <div class="paper-header">
        <h1>Sınav Giriş Belgesi</h1>
      </div>

      <div class="paper-informations">
        <div class="paper-table">
          <div class="paper-table-header">
            <h2>Kişi Bilgileri</h2>
          </div>

          <div class="paper-table-row">
            <div class="field-name">
              <span>Statü:</span>
            </div>

            <div class="field-value">
              <span>{{ exam.role == "student" ? "Aday" : "Gözetmen" }}</span>
            </div>
          </div>

          <div class="paper-table-row">
            <div class="field-name">
              <span>TC No:</span>
            </div>

            <div class="field-value">
              <span>{{ exam.tcno }}</span>
            </div>
          </div>

          <div class="paper-table-row">
            <div class="field-name">
              <span>Ad:</span>
            </div>

            <div class="field-value">
              <span>{{ exam.name }}</span>
            </div>
          </div>

          <div class="paper-table-row">
            <div class="field-name">
              <span>Soyad:</span>
            </div>

            <div class="field-value">
              <span>{{ exam.surname }}</span>
            </div>
          </div>

          <div class="paper-table-row">
            <div class="field-name">
              <span>E-Mail:</span>
            </div>

            <div class="field-value">
              <span>{{ exam.user_email }}</span>
            </div>
          </div>
        </div>

        <div class="paper-table">
          <div class="paper-table-header">
            <h2>Sınav Bilgileri</h2>
          </div>

          <div class="paper-table-row">
            <div class="field-name">
              <span>Sınav Adı:</span>
            </div>

            <div class="field-value">
              <span>{{ exam.exam_name }}</span>
            </div>
          </div>

          <div class="paper-table-row">
            <div class="field-name">
              <span>Sınav Tarihi:</span>
            </div>

            <div class="field-value">
              <span>{{ exam.exam_date }}</span>
            </div>
          </div>

          <div class="paper-table-row">
            <div class="field-name">
              <span>Sınav Dersleri:</span>
            </div>

            <div class="field-value">
              <span
                v-for="(examLesson, index) in exam.exam_lessons"
                :key="index"
                >{{
                  `${examLesson.lesson_subject}${
                    index != exam.exam_lessons.length - 1 ? ", " : ""
                  }`
                }}</span
              >
            </div>
          </div>
        </div>

        <div class="paper-table">
          <div class="paper-table-header">
            <h2>Yerleştirme Bilgileri</h2>
          </div>

          <div class="paper-table-row">
            <div class="field-name">
              <span>Kurum:</span>
            </div>

            <div class="field-value">
              <span>{{ exam.inst_name }}</span>
            </div>
          </div>

          <div class="paper-table-row">
            <div class="field-name">
              <span>Kampüs:</span>
            </div>

            <div class="field-value">
              <span>{{ exam.campus_name }}</span>
            </div>
          </div>

          <div class="paper-table-row">
            <div class="field-name">
              <span>Kampüs Adresi:</span>
            </div>

            <div class="field-value">
              <span>{{ exam.campus_address }}</span>
            </div>
          </div>

          <div class="paper-table-row">
            <div class="field-name">
              <span>Bina:</span>
            </div>

            <div class="field-value">
              <span>{{ exam.building_code }} - {{ exam.building_name }}</span>
            </div>
          </div>

          <div class="paper-table-row">
            <div class="field-name">
              <span>Kat:</span>
            </div>

            <div class="field-value">
              <span>{{ exam.floor_code }} - {{ exam.floor_name }}</span>
            </div>
          </div>

          <div class="paper-table-row">
            <div class="field-name">
              <span>Salon:</span>
            </div>

            <div class="field-value">
              <span>{{ exam.room_name }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="paper-footer">
        <div class="logo">
          <img src="./../../assets/img/mufy-logo.png" alt="logo" />
          <h3>Mufy Sınav Otomasyonu</h3>
        </div>

        <div class="copyright">
          <span>&copy; 2024 Copyright</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ApiService from "@/services/api-service";
import cookies from "js-cookie";
import html2pdf from "html2pdf.js";

export default {
  name: "SinavlarView",
  data() {
    return {
      userId: "",
      examId: "",
      exam: {
        inst_name: "",
        inst_address: "",
        inst_phone: "",
        campus_name: "",
        campus_address: "",
        building_code: "",
        building_name: "",
        floor_code: "",
        floor_name: "",
        room_name: "",
        exam_name: "",
        exam_date: "",
        exam_lessons: [],
        name: "",
        surname: "",
        user_email: "",
        tcno: "",
        role: "",
      },
    };
  },
  async mounted() {
    document.title = "Sınav Belgesi | Mufy";

    this.userId = this.$store.getters.getUser.id
      ? this.$store.getters.getUser.id
      : cookies.get("user-id");
    this.examId = this.$route.params.examId;

    await this.getUserExams();
  },
  methods: {
    async getUserExams() {
      const result = await ApiService.getUserExams(this.userId);
      if (!result.success) {
        return;
      }

      result.user_exams.forEach((exam) => {
        if (exam.exam_id == this.examId) {
          this.exam = exam;
          return;
        }
      });
    },
    slugify(text) {
      return text
        .toString()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .toLowerCase()
        .trim()
        .replace(/\s+/g, "-")
        .replace(/[^\w-]+/g, "")
        .replace(/--+/g, "-");
    },
    downloadPaper() {
      html2pdf()
        .from(document.getElementById("exam-paper"))
        .set({
          margin: 0.5,
          filename: `${this.slugify(this.exam.exam_name)}-${this.slugify(
            this.exam.name
          )}-${this.exam.surname}.pdf`,
          image: { type: "jpeg", quality: 0.98 },
          html2canvas: { scale: 2 },
          jsPDF: { unit: "in", format: "letter", orientation: "portrait" },
        })
        .toPdf()
        .get("pdf")
        .then(function (pdf) {
          window.open(pdf.output("bloburl"), "_blank");
        })
        .save();
    },
  },
};
</script>

<style scoped>
.paper-page {
  padding: 20px;
}

.control-bar {
  display: flex;
  justify-content: space-between;
  margin-bottom: 20px;
}

.control-bar .control-bar-button {
  padding: 10px 20px;
  background-color: var(--color-4);
  color: white;
  border-radius: 5px;
  cursor: pointer;
}

.control-bar .control-bar-button a {
  color: white;
}

.control-bar .control-bar-button i {
  margin-left: 5px;
  font-size: 20px;
}

.paper-container {
  width: 100%;
  border: 1px solid black;
}

.mufy {
  text-align: center;
  margin-bottom: 5px;
}

.mufy img {
  width: 80px;
}

.mufy h3 {
  font-weight: normal;
}

.paper-header {
  width: 100%;
  background-color: var(--color-4);
  display: flex;
  justify-content: center;
  margin-bottom: 20px;
}

.paper-header h1 {
  color: black;
  height: calc(100% + 20px);
  background-color: white;
  width: fit-content;
  padding-inline: 20px;
}

.paper-informations {
  width: 100%;
  padding: 20px;
}

.paper-table {
  width: 100%;
  border: 1px solid black;
  margin-bottom: 20px;
}

.paper-table .paper-table-header {
  width: 100%;
  text-align: center;
  padding: 5px;
  border-bottom: 1px solid black;
}

.paper-table .paper-table-row {
  display: grid;
  grid-template-columns: 300px 1fr;
  border-bottom: 1px solid black;
}

.paper-table .paper-table-row:last-child {
  border-bottom: none;
}

.paper-table-row .field-name {
  text-align: right;
  padding: 5px;
  border-right: 1px solid black;
}

.paper-table-row .field-value {
  padding: 5px;
}

.paper-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding-inline: 20px;
}

.paper-footer .logo {
  display: flex;
  align-items: center;
}

.paper-footer .logo img {
  width: 50px;
  margin-right: 10px;
}

@media screen and (max-width: 600px) {
  .paper-table .paper-table-row {
    grid-template-columns: 100px 1fr;
  }

  .paper-table-row .field-value span {
    overflow-wrap: anywhere;
  }
}

@media screen and (max-width: 425px) {
  .control-bar {
    flex-direction: column;
    row-gap: 10px;
  }

  .control-bar .control-bar-button a,
  .control-bar .control-bar-button span {
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
  }

  .paper-header h1 {
    text-align: center;
    font-size: 15px;
  }

  .mufy h3 {
    font-size: 13px;
  }

  .paper-table-header h2 {
    font-size: 15px;
  }

  .paper-footer .logo img {
    width: 30px;
  }

  .paper-footer .logo h3 {
    font-size: 13px;
  }

  .paper-footer .copyright span {
    font-size: 10px;
  }
}
</style>
