<template>
  <div class="panel-layout">
    <header>
      <div class="logo">
        <i class="fa-solid fa-bars" @click="toggleHamburger"></i>
        <router-link to="/panel">
          <img src="./../assets/img/mufy-logo.png" alt="logo" />
          <span>Mufy</span>
        </router-link>
      </div>

      <div class="user">
        <h3>
          {{ `${user.name}` }}

          <div class="user-process">
            <div class="process-item" @click="logout">
              <a href="#">
                <span>Çıkış yap</span>
                <i class="fa-solid fa-sign-out"></i>
              </a>
            </div>
          </div>
        </h3>
      </div>
    </header>

    <div class="site-container">
      <div class="sidebar" :class="hamburger ? 'hamburger-show' : ''">
        <div class="category">
          <div class="category-title">
            <label for="fiziksel-tanimlamalar">
              <div class="title">
                <i class="fa-solid fa-building"></i>
                <h3>Fiziksel Tanımlamalar</h3>
              </div>

              <i class="fa-solid fa-chevron-down"></i>
            </label>
            <input
              type="checkbox"
              name="fiziksel-tanimlamalar"
              id="fiziksel-tanimlamalar"
            />

            <div class="category-pages">
              <router-link to="/panel/fiziksel-tanimlamalar/kurum"
                >Kurum</router-link
              >
              <router-link to="/panel/fiziksel-tanimlamalar/kampus"
                >Kampüs</router-link
              >
              <router-link to="/panel/fiziksel-tanimlamalar/bina"
                >Bina</router-link
              >
              <router-link to="/panel/fiziksel-tanimlamalar/kat"
                >Kat</router-link
              >
              <router-link to="/panel/fiziksel-tanimlamalar/salon"
                >Salon</router-link
              >
            </div>
          </div>
        </div>

        <div class="category">
          <div class="category-title">
            <label for="akademik-tanimlamalar">
              <div class="title">
                <i class="fa-solid fa-users"></i>
                <h3>Akademik Tanımlamalar</h3>
              </div>

              <i class="fa-solid fa-chevron-down"></i>
            </label>
            <input
              type="checkbox"
              name="akademik-tanimlamalar"
              id="akademik-tanimlamalar"
            />

            <div class="category-pages">
              <router-link to="/panel/akademik-tanimlamalar/ders"
                >Ders</router-link
              >
              <router-link to="/panel/akademik-tanimlamalar/personel"
                >Personel</router-link
              >
              <router-link to="/panel/akademik-tanimlamalar/aday"
                >Aday</router-link
              >
            </div>
          </div>
        </div>

        <div class="category">
          <div class="category-title">
            <label for="sinav">
              <div class="title">
                <i class="fa-solid fa-book"></i>
                <h3>Sınav</h3>
              </div>

              <i class="fa-solid fa-chevron-down"></i>
            </label>
            <input type="checkbox" name="sinav" id="sinav" />

            <div class="category-pages">
              <router-link to="/panel/sinav/sinav-olustur"
                >Sınav Oluştur</router-link
              >
            </div>
          </div>
        </div>
      </div>

      <div class="content">
        <router-view />

        <footer>
          <h3>© 2024 - Mufy</h3>
        </footer>
      </div>
    </div>
  </div>
</template>

<script>
import ApiService from "@/services/api-service";
import cookies from "js-cookie";

export default {
  name: "PanelLayout",
  data() {
    return {
      user: {},
      hamburger: false,
    };
  },
  async created() {
    const userId = cookies.get("user-id");

    if (!userId) {
      this.$router.push("/auth/login");
    }

    const result = await ApiService.getUser(userId);

    if (!result.success) {
      this.$router.push("/auth/login");
    }

    this.$store.dispatch("setUserAction", result.user);

    if (result.user.role != "admin") {
      this.$router.push("/rapor");
    }
    this.user = result.user;
  },
  methods: {
    logout() {
      cookies.remove("user-id");
      this.$store.dispatch("setUserAction", {});

      this.$router.push("/auth/login");
    },
    toggleHamburger() {
      this.hamburger = !this.hamburger;
    },
  },
};
</script>

<style scoped>
.panel-layout {
  width: 100%;
  min-height: 100vh;
}

.site-container {
  width: 100%;
  min-height: calc(100vh - 80px);
  display: grid;
  grid-template-columns: 300px 1fr;
  position: relative;
}

.logo {
  display: flex;
  align-items: center;
}

.logo i {
  margin-right: 10px;
  position: relative;
  z-index: 15000;
  display: none;
}

.sidebar {
  width: 100%;
  height: calc(100vh - 80px);
  background-color: var(--color-4);
  overflow-y: auto;
  position: sticky;
  left: 0px;
  top: 80px;
}

.category {
  width: 100%;
}

.category input[type="checkbox"] {
  display: none;
}

.category .category-title label {
  height: 50px;
  padding-inline: 20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-bottom: 1px solid rgba(196, 196, 196, 0.39);
  color: rgb(212, 212, 212);
  font-weight: 400;
  cursor: pointer;
}

.category-title h3 {
  font-weight: 400;
}

.category-title .title {
  display: grid;
  grid-template-columns: 25px 1fr;
  place-items: center;
}

.category-title .title i {
  margin-right: 10px;
}

.category .category-pages {
  display: flex;
  flex-direction: column;
  background-color: var(--color-5);
  padding-left: 50px;
  padding-right: 10px;
  overflow: hidden;
  max-height: 0px;
  transition: all 0.7s cubic-bezier(0.86, 0, 0.07, 1);
}

.category input:checked + .category-pages {
  max-height: 1000px;
}

.category-pages a:first-child {
  margin-top: 20px;
}

.category-pages a:last-child {
  margin-bottom: 20px;
}

.category-pages a {
  color: rgb(230, 230, 230);
  margin-bottom: 5px;
}

.category-pages a:hover {
  color: white;
}

footer {
  width: 100%;
  height: 50px;
  padding-inline: var(--inline-padding);
  background-color: var(--color-4);
  color: white;
  display: flex;
  align-items: center;
  border-left: 1px solid white;
}

.hamburger-show {
  transform: translateX(0%) !important;
}

@media screen and (max-width: 768px) {
  .logo i {
    display: block;
    font-size: 20px;
    cursor: pointer;
  }

  .sidebar {
    position: fixed;
    top: inherit;
    left: 0px;
    z-index: 1000;
    transition: all 0.5s;
    transform: translateX(-100%);
  }

  .site-container {
    grid-template-columns: 1fr;
  }
}
</style>
