<template>
  <div class="panel-page-content">
    <div class="content-header">
      <h1>Katlar</h1>

      <div class="buttons">
        <div class="button" @click="toggleAddFloorPopup">
          <i class="fa-solid fa-plus"></i>
          Kat Ekle
        </div>
      </div>
    </div>

    <div class="table">
      <div class="table-row table-header">
        <div class="table-cell">Kurum</div>
        <div class="table-cell">Kampüs</div>
        <div class="table-cell">Bina</div>
        <div class="table-cell">Kat Kodu</div>
        <div class="table-cell">Kat Adı</div>
        <div class="table-cell">Kontrol</div>
      </div>

      <div class="table-row" v-for="(item, index) in floors" :key="index">
        <div class="table-cell">
          {{ item.inst_name }}
        </div>
        <div class="table-cell">
          {{ item.campus_name }}
        </div>
        <div class="table-cell">
          {{ item.building_name }}
        </div>
        <div class="table-cell">
          {{ item.floor_code }}
        </div>
        <div class="table-cell">
          {{ item.floor_name }}
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
            @click="deleteFloor(item.id)"
          >
            <i class="fa-solid fa-trash"></i>
          </div>
        </div>
      </div>

      <div class="table-empty" v-if="floors.length == 0">
        <p>Kayıt bulunamadı</p>
      </div>
    </div>
    <div class="table-information">
      <p>Toplam {{ floors.length }} kayıt var</p>
    </div>

    <div class="popup" v-if="addFloorPopup">
      <div class="popup-content">
        <div class="popup-header">
          <h2>Kat Ekle</h2>

          <div class="close-button" @click="toggleAddFloorPopup">
            <i class="fa-solid fa-xmark"></i>
          </div>
        </div>

        <div class="row">
          <span>Kurum Seçiniz</span>
          <select
            name="kurumlar"
            id="kurumlar"
            v-model="addFloorPopupItem.inst_id"
            @change="changeCampuses(addFloorPopupItem.inst_id)"
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
            v-model="addFloorPopupItem.campus_id"
            @change="changeBuildings(addFloorPopupItem.campus_id)"
          >
            <option
              v-for="(campus, index) in addFloorPopupItem.campuses"
              :key="index"
              :value="campus.id"
            >
              {{ campus.campus_name }}
            </option>
          </select>
        </div>

        <div class="row">
          <span>Bina Seçiniz</span>
          <select
            name="kampusler"
            id="kampusler"
            v-model="addFloorPopupItem.building_id"
          >
            <option
              v-for="(building, index) in addFloorPopupItem.buildings"
              :key="index"
              :value="building.id"
            >
              {{ building.building_name }}
            </option>
          </select>
        </div>

        <div class="row">
          <span>Kat Kodu</span>
          <input
            type="text"
            placeholder="Kat Kodu"
            v-model="addFloorPopupItem.floor_code"
          />
        </div>

        <div class="row">
          <span>Kat Adı</span>
          <input
            type="text"
            placeholder="Kat Adı"
            v-model="addFloorPopupItem.floor_name"
          />
        </div>

        <div class="button-row" @click="addFloor">
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
          <select name="kurumlar" id="kurumlar" v-model="editPopupItem.inst_id">
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
          <span>Bina Seçiniz</span>
          <select
            name="kampusler"
            id="kampusler"
            v-model="editPopupItem.building_id"
          >
            <option
              v-for="(building, index) in editPopupItem.buildings"
              :key="index"
              :value="building.id"
            >
              {{ building.building_name }}
            </option>
          </select>
        </div>

        <div class="row">
          <span>Kat Adı</span>
          <input
            type="text"
            placeholder="Kat Adı"
            v-model="editPopupItem.floor_name"
          />
        </div>

        <div class="row">
          <span>Kat Kodu</span>
          <input
            type="text"
            placeholder="Kat Kodu"
            v-model="editPopupItem.floor_code"
          />
        </div>

        <div class="button-row" @click="editFloor(editPopupItem.id)">
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
  name: "KatView",
  data() {
    return {
      userId: "",
      institutions: [],
      campuses: [],
      buildings: [],
      floors: [],
      addFloorPopup: false,
      addFloorPopupItem: {
        inst_id: "",
        campuses: [],
        campus_id: "",
        buildings: [],
        building_id: "",
        floor_code: "",
        floor_name: "",
      },
      editPopup: false,
      editPopupItem: {
        inst_id: "",
        campuses: [],
        campus_id: "",
        buildings: [],
        building_id: "",
        floor_code: "",
        floor_name: "",
      },
    };
  },
  async mounted() {
    document.title = "Katlar | Mufy";

    this.userId = this.$store.getters.getUser.id
      ? this.$store.getters.getUser.id
      : cookies.get("user-id");

    await this.getFloors();
    await this.getInstitutions();
    await this.getCampuses();
    await this.getBuildings();
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
    async getFloors() {
      const result = await ApiService.getFloors(this.userId);
      if (!result.success) {
        return;
      }

      this.floors = result.floors;
    },
    async deleteFloor(id) {
      const result = await ApiService.deleteFloorItem(this.userId, id);

      if (!result.success) {
        return;
      }

      await this.getFloors();
    },
    toggleAddFloorPopup() {
      this.addFloorPopup = !this.addFloorPopup;
    },
    changeCampuses(instId) {
      this.addFloorPopupItem.campuses = this.campuses.filter(
        (campus) => campus.inst_id === instId
      );
      this.addFloorPopupItem.buildings = [];

      this.editPopupItem.campuses = this.campuses.filter(
        (campus) => campus.inst_id === instId
      );
      this.editPopupItem.buildings = [];
    },
    changeBuildings(campusId) {
      this.addFloorPopupItem.buildings = this.buildings.filter(
        (building) => building.campus_id === campusId
      );

      this.editPopupItem.buildings = this.buildings.filter(
        (building) => building.campus_id === campusId
      );
    },
    async addFloor() {
      const result = await ApiService.addFloor(
        this.userId,
        this.addFloorPopupItem
      );

      if (!result.success) {
        return;
      }

      this.addFloorPopup = false;
      this.addFloorPopupItem = {
        inst_id: "",
        campuses: [],
        campus_id: "",
        buildings: [],
        building_id: "",
        floor_code: "",
        floor_name: "",
      };

      await this.getFloors();
    },
    async toggleEditPopup(item = null) {
      this.editPopupItem = item;
      this.changeCampuses(item.inst_id);
      this.changeBuildings(item.campus_id);
      this.editPopup = !this.editPopup;
    },
    async editFloor(id) {
      const result = await ApiService.updateFloor(this.userId, {
        ...this.editPopupItem,
        id,
      });

      if (!result.success) {
        return;
      }

      this.editPopup = false;
      this.floors = this.floors.map((item) => {
        if (item.id === id) {
          return this.editPopupItem;
        }

        return item;
      });
    },
  },
};
</script>

<style scoped>
@import url("../../assets/style/panel-pages.css");

.table-row {
  grid-template-columns: repeat(6, 1fr);
}
</style>
