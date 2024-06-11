<template>
  <div class="rapor-layout">
    <header>
      <div class="logo">
        <router-link to="/rapor">
          <img src="./../assets/img/mufy-logo.png" alt="logo" />
          <span>Mufy</span>
        </router-link>
      </div>

      <div class="user" @click="logoutMobile">
        <h3>
          {{ `${user.name} ${user.surname}` }}

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

    <div class="rapor-page-content">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
import ApiService from "@/services/api-service";
import cookies from "js-cookie";

export default {
  name: "RaporLayout",
  data() {
    return {
      user: {},
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

    this.user = result.user;
  },
  methods: {
    logout() {
      cookies.remove("user-id");
      this.$store.dispatch("setUserAction", {});

      this.$router.push("/auth/login");
    },
    logoutMobile() {
      if (window.innerWidth < 768) {
        this.logout();
      }
    },
  },
};
</script>

<style scoped>
.rapor-layout {
  width: 100%;
  min-height: calc(100vh - 60px);
}

.rapor-page-content {
  width: 100%;
}
</style>
