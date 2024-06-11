<template>
  <div class="panel-page-content">
    <div class="content-header">
      <h1>Binalar</h1>

      <div class="buttons">
        <div class="button" @click="toggleAddBuildingPopup">
          <i class="fa-solid fa-plus"></i>
          Bina Ekle
        </div>
      </div>
    </div>

    <div class="table">
      <div class="table-row table-header">
        <div class="table-cell">Kurum</div>
        <div class="table-cell">Kampüs</div>
        <div class="table-cell">Kodu</div>
        <div class="table-cell">Adı</div>
        <div class="table-cell">Kontrol</div>
      </div>

      <div class="table-row" v-for="(item, index) in buildings" :key="index">
        <div class="table-cell">
          {{ item.inst_name }}
        </div>
        <div class="table-cell">
          {{ item.campus_name }}
        </div>
        <div class="table-cell">
          {{ item.building_code }}
        </div>
        <div class="table-cell">
          {{ item.building_name }}
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
            @click="deleteBuilding(item.id)"
          >
            <i class="fa-solid fa-trash"></i>
          </div>
        </div>
      </div>

      <div class="table-empty" v-if="buildings.length == 0">
        <p>Kayıt bulunamadı</p>
      </div>
    </div>
    <div class="table-information">
      <p>Toplam {{ buildings.length }} kayıt var</p>
    </div>

    <div class="popup" v-if="addBuildingPopup">
      <div class="popup-content">
        <div class="popup-header">
          <h2>Bina Ekle</h2>

          <div class="close-button" @click="toggleAddBuildingPopup">
            <i class="fa-solid fa-xmark"></i>
          </div>
        </div>

        <div class="row">
          <span>Kurum Seçiniz</span>
          <select
            name="kurumlar"
            id="kurumlar"
            v-model="addBuildingPopupItem.inst_id"
            @change="changeCampuses(addBuildingPopupItem.inst_id)"
          >
            <option
              v-for="(inst, index) in institutions"
              :key="index"
              :value="inst.id"
            >
              {{ inst.inst_name }}
            </option>
          </select>
        </div>

        <div class="row">
          <span>Kampüs Seçiniz</span>
          <select
            name="kampusler"
            id="kampusler"
            v-model="addBuildingPopupItem.campus_id"
          >
            <option
              v-for="(campus, index) in addBuildingPopupItem.campuses"
              :key="index"
              :value="campus.id"
            >
              {{ campus.campus_name }}
            </option>
          </select>
        </div>

        <div class="row">
          <span>Bina Adı</span>
          <input
            type="text"
            placeholder="Bina Adı"
            v-model="addBuildingPopupItem.building_name"
          />
        </div>

        <div class="row">
          <span>Bina Kodu</span>
          <input
            type="text"
            placeholder="Bina Kodu"
            v-model="addBuildingPopupItem.building_code"
          />
        </div>

        <div class="button-row" @click="addBuilding">
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
          <span>Kurum Seçiniz</span>
          <select
            name="kurumlar"
            id="kurumlar"
            v-model="editPopupItem.inst_id"
            @change="changeCampuses(editPopupItem.inst_id)"
          >
            <option
              v-for="(inst, index) in institutions"
              :key="index"
              :value="inst.id"
            >
              {{ inst.inst_name }}
            </option>
          </select>
        </div>

        <div class="row">
          <span>Kampüs Seçiniz</span>
          <select
            name="kampusler"
            id="kampusler"
            v-model="editPopupItem.campus_id"
            @change="getCampuses"
          >
            <option
              v-for="(campus, index) in editPopupItem.campuses"
              :key="index"
              :value="campus.id"
            >
              {{ campus.campus_name }}
            </option>
          </select>
        </div>

        <div class="row">
          <span>Bina Adı</span>
          <input
            type="text"
            placeholder="Bina Adı"
            v-model="editPopupItem.building_name"
          />
        </div>

        <div class="row">
          <span>Bina Kodu</span>
          <input
            type="text"
            placeholder="Bina Kodu"
            v-model="editPopupItem.building_code"
          />
        </div>

        <div class="button-row" @click="editBuilding(editPopupItem.id)">
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
  name: "BinaView",
  data() {
    return {
      userId: "",
      addBuildingPopup: false,
      addBuildingPopupItem: {
        inst_id: "",
        campus_id: "",
        building_code: "",
        building_name: "",
        campuses: [],
      },
      institutions: [],
      campuses: [],
      buildings: [],
      editPopup: false,
      editPopupItem: {
        inst_id: "",
        campuses: [],
        campus_id: "",
        building_code: "",
        building_name: "",
      },
    };
  },
  async mounted() {
    document.title = "Binalar | Mufy";

    this.userId = this.$store.getters.getUser.id
      ? this.$store.getters.getUser.id
      : cookies.get("user-id");

    await this.getBuildings();
    await this.getInstitutions();
    await this.getCampuses();
  },
  methods: {
    async getInstitutions() {
      const result = await ApiService.getInstitutions(this.userId);
      if (!result.success) {
        return;
      }

      this.institutions = result.institutions;
    },
    async getCampuses() {
      const result = await ApiService.getCampuses(this.userId);
      if (!result.success) {
        return;
      }

      this.campuses = result.campuses;
    },
    async getBuildings() {
      const result = await ApiService.getBuildings(this.userId);
      if (!result.success) {
        return;
      }

      this.buildings = result.buildings;
    },
    toggleAddBuildingPopup() {
      this.addBuildingPopup = !this.addBuildingPopup;
    },
    async addBuilding() {
      const result = await ApiService.addBuilding(
        this.userId,
        this.addBuildingPopupItem
      );

      if (!result.success) {
        return;
      }

      this.addBuildingPopup = false;
      this.addBuildingPopupItem = {
        inst_id: "",
        campus_id: "",
        building_code: "",
        building_name: "",
      };

      await this.getBuildings();
    },
    async toggleEditPopup(item = null) {
      this.editPopupItem = item;
      this.changeCampuses(item.inst_id);
      this.editPopup = !this.editPopup;
    },
    async editBuilding(id) {
      const result = await ApiService.updateBuilding(this.userId, {
        id,
        ...this.editPopupItem,
      });

      if (!result.success) {
        return;
      }

      this.editPopup = false;
      this.buildings = this.buildings.map((item) => {
        if (item.id === id) {
          return this.editPopupItem;
        }

        return item;
      });
    },
    changeCampuses(instId) {
      this.addBuildingPopupItem.campus_id = "";
      this.addBuildingPopupItem.campuses = this.campuses.filter(
        (campus) => campus.inst_id === instId
      );

      this.editPopupItem.campuses = this.campuses.filter(
        (campus) => campus.inst_id === instId
      );
    },
    async deleteBuilding(id) {
      const result = await ApiService.deleteBuildingItem(this.userId, id);

      if (!result.success) {
        return;
      }

      this.buildings = this.buildings.filter((item) => item.id !== id);
    },
  },
};
</script>

<style scoped>
@import url("../../assets/style/panel-pages.css");
</style>
