<template>
  <div class="panel-page-content">
    <div class="content-header">
      <h1>Kurumlar</h1>

      <div class="buttons">
        <div class="button" @click="toggleAddInstitutionPopup">
          <i class="fa-solid fa-plus"></i>
          Kurum Ekle
        </div>
      </div>
    </div>

    <div class="table">
      <div class="table-row table-header">
        <div class="table-cell">Kurum Adı</div>
        <div class="table-cell">Adres</div>
        <div class="table-cell">Telefon</div>
        <div class="table-cell">Ana Kurum</div>
        <div class="table-cell">Kontrol</div>
      </div>

      <div class="table-row" v-for="(item, index) in institutions" :key="index">
        <div class="table-cell">
          {{ item.inst_name }}
        </div>
        <div class="table-cell">
          {{ item.inst_address }}
        </div>
        <div class="table-cell">
          {{ item.inst_phone }}
        </div>
        <div class="table-cell">
          {{ item.main_inst }}
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
            @click="deleteInstitution(item.id)"
          >
            <i class="fa-solid fa-trash"></i>
          </div>
        </div>
      </div>

      <div class="table-empty" v-if="institutions.length == 0">
        <p>Kayıt bulunamadı</p>
      </div>
    </div>
    <div class="table-information">
      <p>Toplam {{ institutions.length }} kayıt var</p>
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
          <span>Kurum Adı</span>
          <input
            type="text"
            placeholder="Kurum Adı"
            v-model="editPopupItem.inst_name"
          />
        </div>

        <div class="row">
          <span>İl</span>
          <input
            type="text"
            placeholder="İl"
            v-model="editPopupItem.inst_city"
          />
        </div>

        <div class="row">
          <span>İlçe</span>
          <input
            type="text"
            placeholder="İlçe"
            v-model="editPopupItem.inst_county"
          />
        </div>

        <div class="row">
          <span>Adres</span>
          <input
            type="text"
            placeholder="Adres"
            v-model="editPopupItem.inst_address"
          />
        </div>

        <div class="row">
          <span>Telefon</span>
          <input
            type="text"
            placeholder="Telefon"
            v-model="editPopupItem.inst_phone"
          />
        </div>

        <div class="row">
          <span>Ana kurum mu?</span>
          <select
            name="main_inst"
            id="main_inst"
            v-model="editPopupItem.main_inst"
          >
            <option value="Evet">Evet</option>
            <option value="Hayır">Hayır</option>
          </select>
        </div>

        <div class="button-row" @click="saveInstitution">
          <div class="button">Kaydet</div>
        </div>
      </div>
    </div>

    <div class="popup" v-if="addInstitutionPopup">
      <div class="popup-content">
        <div class="popup-header">
          <h2>Kurum Ekle</h2>

          <div class="close-button" @click="toggleAddInstitutionPopup">
            <i class="fa-solid fa-xmark"></i>
          </div>
        </div>

        <div class="row">
          <span>Kurum Adı</span>
          <input
            type="text"
            placeholder="Kurum Adı"
            v-model="addInstitutionPopupItem.inst_name"
          />
        </div>

        <div class="row">
          <span>İl</span>
          <input
            type="text"
            placeholder="İl"
            v-model="addInstitutionPopupItem.inst_city"
          />
        </div>

        <div class="row">
          <span>İlçe</span>
          <input
            type="text"
            placeholder="İlçe"
            v-model="addInstitutionPopupItem.inst_county"
          />
        </div>

        <div class="row">
          <span>Adres</span>
          <input
            type="text"
            placeholder="Adres"
            v-model="addInstitutionPopupItem.inst_address"
          />
        </div>

        <div class="row">
          <span>Telefon</span>
          <input
            type="text"
            placeholder="Telefon"
            v-model="addInstitutionPopupItem.inst_phone"
          />
        </div>

        <div class="row">
          <span>Ana kurum mu?</span>
          <select
            name="main_inst"
            id="main_inst"
            v-model="addInstitutionPopupItem.main_inst"
          >
            <option value="Evet">Evet</option>
            <option value="Hayır">Hayır</option>
          </select>
        </div>

        <div class="button-row" @click="addInstitution">
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
  name: "KurumView",
  data() {
    return {
      userId: "",
      editPopup: false,
      editPopupItem: {
        inst_name: "",
        inst_city: "",
        inst_county: "",
        inst_address: "",
        inst_phone: "",
        main_inst: "",
      },
      addInstitutionPopup: false,
      addInstitutionPopupItem: {
        inst_name: "",
        inst_city: "",
        inst_county: "",
        inst_address: "",
        inst_phone: "",
        main_inst: "",
      },
      institutions: [],
    };
  },
  async mounted() {
    document.title = "Kurumlar | Mufy";

    this.userId = this.$store.getters.getUser.id
      ? this.$store.getters.getUser.id
      : cookies.get("user-id");

    await this.getInstitutions();
  },
  methods: {
    async getInstitutions() {
      const result = await ApiService.getInstitutions(this.userId);

      if (!result.success) {
        return;
      }

      this.institutions = result.institutions;
    },
    async deleteInstitution(institutionId) {
      const result = await ApiService.deleteInstitutionItem(
        this.userId,
        institutionId
      );

      if (!result.success) {
        return;
      }

      this.institutions = this.institutions.filter(
        (item) => item.id !== institutionId
      );
    },
    toggleEditPopup(item = null) {
      this.editPopupItem = item;
      this.editPopup = !this.editPopup;
    },
    async saveInstitution() {
      const result = await ApiService.updateInstitution(
        this.userId,
        this.editPopupItem
      );

      if (!result.success) {
        return;
      }

      this.institutions = this.institutions.map((item) => {
        if (item.id === this.editPopupItem.id) {
          return this.editPopupItem;
        }

        return item;
      });

      this.toggleEditPopup();
    },
    toggleAddInstitutionPopup() {
      this.addInstitutionPopup = !this.addInstitutionPopup;
    },
    async addInstitution() {
      const result = await ApiService.addInstitution(
        this.userId,
        this.addInstitutionPopupItem
      );

      if (!result.success) {
        return;
      }

      await this.getInstitutions();
      this.toggleAddInstitutionPopup();
    },
  },
};
</script>

<style scoped>
@import url("../../assets/style/panel-pages.css");
</style>
