<template>
  <div class="panel-page-content">
    <div class="content-header">
      <h1>Kampüsler</h1>

      <div class="buttons">
        <div class="button" @click="toggleAddCampusPopup">
          <i class="fa-solid fa-plus"></i>
          Kampüs Ekle
        </div>
      </div>
    </div>

    <div class="table">
      <div class="table-row table-header">
        <div class="table-cell">İsim</div>
        <div class="table-cell">İl</div>
        <div class="table-cell">İlçe</div>
        <div class="table-cell">Adres</div>
        <div class="table-cell">Kontrol</div>
      </div>

      <div class="table-row" v-for="(item, index) in campuses" :key="index">
        <div class="table-cell">
          {{ item.campus_name }}
        </div>
        <div class="table-cell">
          {{ item.city }}
        </div>
        <div class="table-cell">
          {{ item.county }}
        </div>
        <div class="table-cell">
          {{ item.address }}
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
            @click="deleteCampus(item.id)"
          >
            <i class="fa-solid fa-trash"></i>
          </div>
        </div>
      </div>

      <div class="table-empty" v-if="campuses.length == 0">
        <p>Kayıt bulunamadı</p>
      </div>
    </div>
    <div class="table-information">
      <p>Toplam {{ campuses.length }} kayıt var</p>
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
              v-for="(institution, index) in institutions"
              :key="index"
              :value="institution.id"
            >
              {{ institution.inst_name }}
            </option>
          </select>
        </div>

        <div class="row">
          <span>Kampüs Adı</span>
          <input
            type="text"
            placeholder="Kampüs Adı"
            v-model="editPopupItem.campus_name"
          />
        </div>

        <div class="row">
          <span>İl</span>
          <input type="text" placeholder="İl" v-model="editPopupItem.city" />
        </div>

        <div class="row">
          <span>İlçe</span>
          <input
            type="text"
            placeholder="İlçe"
            v-model="editPopupItem.county"
          />
        </div>

        <div class="row">
          <span>Adres</span>
          <input
            type="text"
            placeholder="Adres"
            v-model="editPopupItem.address"
          />
        </div>

        <div class="button-row" @click="saveCampus(editPopupItem.id)">
          <div class="button">Kaydet</div>
        </div>
      </div>
    </div>

    <div class="popup" v-if="addCampusPopup">
      <div class="popup-content">
        <div class="popup-header">
          <h2>Kampüs Ekle</h2>

          <div class="close-button" @click="toggleAddCampusPopup">
            <i class="fa-solid fa-xmark"></i>
          </div>
        </div>

        <div class="row">
          <span>Kurum Seçiniz</span>
          <select
            name="kurumlar"
            id="kurumlar"
            v-model="addCampusPopupItem.inst_id"
          >
            <option
              v-for="(item, index) in institutions"
              :key="index"
              :value="item.id"
            >
              {{ item.inst_name }}
            </option>
          </select>
        </div>

        <div class="row">
          <span>Kampüs Adı</span>
          <input
            type="text"
            placeholder="Kampüs Adı"
            v-model="addCampusPopupItem.campus_name"
          />
        </div>

        <div class="row">
          <span>İl</span>
          <input
            type="text"
            placeholder="İl"
            v-model="addCampusPopupItem.city"
          />
        </div>

        <div class="row">
          <span>İlçe</span>
          <input
            type="text"
            placeholder="İlçe"
            v-model="addCampusPopupItem.county"
          />
        </div>

        <div class="row">
          <span>Adres</span>
          <input
            type="text"
            placeholder="Adres"
            v-model="addCampusPopupItem.address"
          />
        </div>

        <div class="button-row" @click="addCampus">
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
  name: "KampusView",
  data() {
    return {
      userId: "",
      editPopup: false,
      editPopupItem: {},
      addCampusPopup: false,
      addCampusPopupItem: {
        inst_id: "",
        campus_name: "",
        city: "",
        county: "",
        address: "",
      },
      campuses: [],
      institutions: [],
    };
  },
  async mounted() {
    document.title = "Kampüsler | Mufy";

    this.userId = this.$store.getters.getUser.id
      ? this.$store.getters.getUser.id
      : cookies.get("user-id");

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
    toggleEditPopup(item = null) {
      this.editPopupItem = item;
      this.editPopup = !this.editPopup;
    },
    toggleAddCampusPopup() {
      this.addCampusPopup = !this.addCampusPopup;
    },
    async saveCampus() {
      const result = await ApiService.updateCampus(
        this.userId,
        this.editPopupItem
      );

      if (!result.success) {
        return;
      }

      this.editPopup = false;
    },
    async addCampus() {
      const result = await ApiService.addCampus(
        this.userId,
        this.addCampusPopupItem
      );

      if (!result.success) {
        return;
      }

      await this.getCampuses();
      this.addCampusPopup = false;
    },
    async deleteCampus(id) {
      const result = await ApiService.deleteCampusItem(this.userId, id);

      if (!result.success) {
        return;
      }

      this.campuses = this.campuses.filter((item) => item.id !== id);
    },
  },
};
</script>

<style scoped>
@import url("../../assets/style/panel-pages.css");
</style>
