<template>
  <div class="login-container">
    <h1>Giriş Yap</h1>
    <p>MUFY Sınav Koordinasyon Sistemi</p>
    <input type="text" placeholder="Tc Kimlik No" v-model="tcno" />
    <input type="password" placeholder="Şifre" v-model="password" />

    <div class="row">
      <button @click="login">Giriş</button>
    </div>
  </div>
</template>

<script>
import ApiService from "@/services/api-service";
import cookies from "js-cookie";

export default {
  name: "LoginView",
  data() {
    return {
      tcno: "",
      password: "",
    };
  },
  created() {
    document.title = "Giriş Yap | Mufy";
  },
  methods: {
    async login() {
      if (!this.validateLogin()) {
        return alert("Lütfen tüm alanları doldurun.");
      }

      const result = await ApiService.login({
        tcno: this.tcno,
        password: this.password,
      });

      if (!result.success) {
        return alert("Giriş başarısız. Lütfen bilgilerinizi kontrol edin.");
      }

      cookies.set("user-id", result.user.id);
      this.$store.dispatch("setUserAction", result.user);

      if (result.user.role == "admin") {
        this.$router.push("/panel");
      } else {
        this.$router.push("/rapor");
      }
    },
    validateLogin() {
      return this.tcno.length > 0 && this.password.length > 0;
    },
  },
};
</script>

<style scoped>
.login-container {
  padding: 20px;
  text-align: center;
  border: 1px solid #ccc;
  border-radius: 5px;
  width: 500px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.row {
  width: 100%;
  display: flex;
  justify-content: flex-end;
  align-items: center;
}

button {
  padding: 10px 20px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

button:hover {
  background-color: #0056b3;
}

input {
  width: 100%;
  padding: 10px;
  margin: 10px 0;
  border: 1px solid #ccc;
  border-radius: 5px;
  background-color: #f9f9f9;
}

input:focus {
  background-color: white;
}

h1 {
  margin-bottom: 10px;
}
</style>
