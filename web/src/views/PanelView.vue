<template>
  <div class="main-panel">
    <h1 class="panel-title">Gösterge Paneli</h1>

    <div class="statistics">
      <div class="statistic-item">
        <div class="icon">
          <i class="fa-solid fa-graduation-cap"></i>
        </div>

        <div class="informations">
          <h3>Öğrenci Sayısı</h3>
          <p>{{ statistics.studentCount }}</p>
        </div>
      </div>

      <div class="statistic-item">
        <div class="icon">
          <i class="fa-solid fa-person-chalkboard"></i>
        </div>

        <div class="informations">
          <h3>Personel Sayısı</h3>
          <p>{{ statistics.teacherCount }}</p>
        </div>
      </div>

      <div class="statistic-item">
        <div class="icon">
          <i class="fa-solid fa-building"></i>
        </div>

        <div class="informations">
          <h3>Aktif Salon Sayısı</h3>
          <p>{{ statistics.activeClassroomCount }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ApiService from "@/services/api-service";
import cookies from "js-cookie";

export default {
  name: "PanelView",
  data() {
    return {
      statistics: {
        studentCount: 0,
        teacherCount: 0,
        activeClassroomCount: 0,
      },
    };
  },
  mounted() {
    document.title = "Panel | Mufy";

    this.getStatistics();
  },
  methods: {
    async getStatistics() {
      const userId = this.$store.getters.getUser.id
        ? this.$store.getters.getUser.id
        : cookies.get("user-id");

      const result = await ApiService.getPanelStatistics(userId);

      if (!result.success) {
        return alert("İstatistikler getirilirken bir hata oluştu.");
      }

      this.statistics = result.statistics;
    },
  },
};
</script>

<style scoped>
.main-panel {
  width: 100%;
  min-height: calc(100vh - 80px - 50px);
  padding: var(--block-padding) var(--inline-padding);
}

.panel-title {
  margin-bottom: 20px;
}

.statistics {
  width: 100%;
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  column-gap: 15px;
}

.statistic-item {
  width: 100%;
  height: 125px;
  background-color: var(--color-6);
  display: grid;
  grid-template-columns: 100px 1fr;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
}

.statistic-item .icon {
  background-color: var(--color-4);
  color: white;
  display: grid;
  place-items: center;
  font-size: 40px;
}

.statistic-item .informations {
  padding: 10px;
}

.statistic-item .informations h3 {
  margin-bottom: 10px;
}

.statistic-item .informations p {
  font-size: 18px;
}

@media screen and (max-width: 768px) {
  .statistics {
    grid-template-columns: 1fr;
    row-gap: 15px;
  }
}

@media screen and (max-width: 300px) {
  .statistic-item {
    grid-template-columns: 50px 1fr;
  }

  .statistic-item i {
    font-size: 20px;
  }
}
</style>
