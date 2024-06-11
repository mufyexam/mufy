<template>
  <div class="panel-page-content">
    <div class="content-header">
      <h1>Salonlar</h1>

      <div class="buttons">
        <div class="button" @click="toggleAddRoomPopup">
          <i class="fa-solid fa-plus"></i>
          Salon Ekle
        </div>
      </div>
    </div>

    <div class="table">
      <div class="table-row table-header">
        <div class="table-cell">Kurum</div>
        <div class="table-cell">Kampüs</div>
        <div class="table-cell">Bina</div>
        <div class="table-cell">Kat</div>
        <div class="table-cell">Salon Adı</div>
        <div class="table-cell">Kapasite</div>
        <div class="table-cell">Uygun mu?</div>
        <div class="table-cell">Kontrol</div>
      </div>

      <div class="table-row" v-for="(item, index) in rooms" :key="index">
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
          {{ item.floor_name }}
        </div>
        <div class="table-cell">
          {{ item.room_name }}
        </div>
        <div class="table-cell">
          {{ item.capacity }}
        </div>
        <div class="table-cell">
          {{ item.availability == 1 ? "Evet" : "Hayır" }}
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
            @click="deleteRoom(item.id)"
          >
            <i class="fa-solid fa-trash"></i>
          </div>
        </div>
      </div>

      <div class="table-empty" v-if="rooms.length == 0">
        <p>Kayıt bulunamadı</p>
      </div>
    </div>
    <div class="table-information">
      <p>Toplam {{ rooms.length }} kayıt var</p>
    </div>

    <div class="popup" v-if="addRoomPopup">
      <div class="popup-content">
        <div class="popup-header">
          <h2>Salon Ekle</h2>

          <div class="close-button" @click="toggleAddRoomPopup">
            <i class="fa-solid fa-xmark"></i>
          </div>
        </div>

        <div class="row">
          <span>Kurum Seçiniz</span>
          <select
            name="kurumlar"
            id="kurumlar"
            v-model="addRoomPopupItem.inst_id"
            @change="changeCampuses(addRoomPopupItem.inst_id)"
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
            v-model="addRoomPopupItem.campus_id"
            @change="changeBuildings(addRoomPopupItem.campus_id)"
          >
            <option
              v-for="(campus, index) in addRoomPopupItem.campuses"
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
            v-model="addRoomPopupItem.building_id"
            @change="changeFloors(addRoomPopupItem.building_id)"
          >
            <option
              v-for="(building, index) in addRoomPopupItem.buildings"
              :key="index"
              :value="building.id"
            >
              {{ building.building_name }}
            </option>
          </select>
        </div>

        <div class="row">
          <span>Kat Seçiniz</span>
          <select name="katlar" id="katlar" v-model="addRoomPopupItem.floor_id">
            <option
              v-for="(floors, index) in addRoomPopupItem.floors"
              :key="index"
              :value="floors.id"
            >
              {{ floors.floor_name }}
            </option>
          </select>
        </div>

        <div class="row">
          <span>Salon Adı</span>
          <input
            type="text"
            placeholder="Salon Adı"
            v-model="addRoomPopupItem.room_name"
          />
        </div>

        <div class="row">
          <span>Kapasite</span>
          <input
            type="number"
            placeholder="Kapasite"
            v-model="addRoomPopupItem.capacity"
          />
        </div>

        <div class="row">
          <span>Uygun mu?</span>
          <select
            name="availability"
            id="availability"
            v-model="addRoomPopupItem.availability"
          >
            <option :value="1">Evet</option>
            <option :value="0">Hayır</option>
          </select>
        </div>

        <div class="button-row" @click="addRoom">
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
            @change="changeBuildings(editPopupItem.campus_id)"
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
            @change="changeFloors(editPopupItem.building_id)"
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
          <span>Kat Seçiniz</span>
          <select name="katlar" id="katlar" v-model="editPopupItem.floor_id">
            <option
              v-for="(floors, index) in editPopupItem.floors"
              :key="index"
              :value="floors.id"
            >
              {{ floors.floor_name }}
            </option>
          </select>
        </div>

        <div class="row">
          <span>Salon Adı</span>
          <input
            type="text"
            placeholder="Salon Adı"
            v-model="editPopupItem.room_name"
          />
        </div>

        <div class="row">
          <span>Kapasite</span>
          <input
            type="number"
            placeholder="Kapasite"
            v-model="editPopupItem.capacity"
          />
        </div>

        <div class="row">
          <span>Uygun mu?</span>
          <select
            name="availability"
            id="availability"
            v-model="editPopupItem.availability"
          >
            <option :value="1">Evet</option>
            <option :value="0">Hayır</option>
          </select>
        </div>

        <div class="button-row" @click="editRoom(editPopupItem.id)">
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
  name: "SalonView",
  data() {
    return {
      userId: "",
      institutions: [],
      campuses: [],
      buildings: [],
      floors: [],
      rooms: [],
      addRoomPopup: false,
      addRoomPopupItem: {
        inst_id: "",
        campuses: [],
        campus_id: "",
        buildings: [],
        building_id: "",
        floors: [],
        floor_id: "",
        room_name: "",
        capacity: "",
        availability: false,
      },
      editPopup: false,
      editPopupItem: {
        inst_id: "",
        campuses: [],
        campus_id: "",
        buildings: [],
        building_id: "",
        floors: [],
        floor_id: "",
        room_name: "",
        capacity: "",
        availability: false,
      },
    };
  },
  async mounted() {
    document.title = "Salonlar | Mufy";

    this.userId = this.$store.getters.getUser.id
      ? this.$store.getters.getUser.id
      : cookies.get("user-id");

    await this.getInstitutions();
    await this.getCampuses();
    await this.getBuildings();
    await this.getFloors();
    await this.getRooms();
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
    async getRooms() {
      const result = await ApiService.getRooms(this.userId);
      if (!result.success) {
        return;
      }

      this.rooms = result.rooms;
    },
    toggleAddRoomPopup() {
      this.addRoomPopup = !this.addRoomPopup;
    },
    changeCampuses(instId) {
      this.addRoomPopupItem.campus_id = "";
      this.addRoomPopupItem.building_id = "";
      this.addRoomPopupItem.floor_id = "";

      this.addRoomPopupItem.campuses = this.campuses.filter(
        (campus) => campus.inst_id === instId
      );

      this.editPopupItem.campuses = this.campuses.filter(
        (campus) => campus.inst_id === instId
      );
      this.editPopupItem.buildings = [];
      this.editPopupItem.floors = [];
    },
    changeBuildings(campusId) {
      this.addRoomPopupItem.building_id = "";
      this.addRoomPopupItem.floor_id = "";

      this.addRoomPopupItem.buildings = this.buildings.filter(
        (building) => building.campus_id === campusId
      );

      this.editPopupItem.buildings = this.buildings.filter(
        (building) => building.campus_id === campusId
      );
      this.editPopupItem.floors = [];
    },
    changeFloors(buildingId) {
      this.addRoomPopupItem.floor_id = "";

      this.addRoomPopupItem.floors = this.floors.filter(
        (floor) => floor.building_id === buildingId
      );

      this.editPopupItem.floors = this.floors.filter(
        (floor) => floor.building_id === buildingId
      );
    },
    async addRoom() {
      const result = await ApiService.addRoom(
        this.userId,
        this.addRoomPopupItem
      );

      if (!result.success) {
        return;
      }

      await this.getRooms();
      this.addRoomPopup = false;
      this.addRoomPopupItem = {
        inst_id: "",
        campuses: [],
        campus_id: "",
        buildings: [],
        building_id: "",
        floors: [],
        floor_id: "",
        room_name: "",
        capacity: "",
        availability: false,
      };
    },
    toggleEditPopup(item = null) {
      this.editPopupItem = item;
      this.changeCampuses(item.inst_id);
      this.changeBuildings(item.campus_id);
      this.changeFloors(item.building_id);
      this.editPopup = !this.editPopup;
    },
    async editRoom(roomId) {
      const result = await ApiService.updateRoom(this.userId, {
        ...this.editPopupItem,
        id: roomId,
      });

      if (!result.success) {
        return;
      }

      await this.getRooms();
      this.editPopup = false;
      this.editPopupItem = {
        inst_id: "",
        campuses: [],
        campus_id: "",
        buildings: [],
        building_id: "",
        floors: [],
        floor_id: "",
        room_name: "",
        capacity: "",
        availability: false,
      };

      this.editPopup = false;
    },
    async deleteRoom(roomId) {
      const result = await ApiService.deleteRoomItem(this.userId, roomId);

      if (!result.success) {
        return;
      }

      this.rooms = this.rooms.filter((item) => item.id !== roomId);
    },
  },
};
</script>

<style scoped>
@import url("../../assets/style/panel-pages.css");

.table-row {
  grid-template-columns: repeat(8, 1fr);
}
</style>
