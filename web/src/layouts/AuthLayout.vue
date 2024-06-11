<template>
  <div class="auth-layout">
    <div class="form">
      <router-view />
    </div>

    <div class="banner">
      <img class="logo" src="./../assets/img/logo.png" alt="logo" />
      <h3>Sınav Otomasyonu</h3>
      <img class="banner-gif" src="./../assets/img/auth.gif" alt="auth-gif" />
    </div>
  </div>
</template>

<script>
export default {
  name: "AuthLayout",
};
</script>

<style scoped>
.auth-layout {
  width: 100%;
  min-height: 100vh;
  display: grid;
  grid-template-columns: 50% 50%;
  place-items: center;
}

.login-container {
  background-color: white;
}

.banner {
  width: 100%;
  height: 100%;
  background-color: var(--color-5);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.banner .logo {
  width: 350px;
}

.banner .banner-gif {
  width: 450px;
}

.banner h3 {
  color: white;
  font-size: 2rem;
  margin-block: 20px;
  font-weight: 400;
  letter-spacing: 2px;
}

@media screen and (max-width: 1025px) {
  .auth-layout {
    display: flex;
    flex-direction: column-reverse;
    row-gap: 20px;
    justify-content: start;
    height: 100vh;
  }

  .banner {
    padding-block: 20px;
    height: auto;
  }

  .banner .logo {
    width: 200px;
  }

  .banner h3 {
    font-size: 20px;
  }

  .banner .banner-gif {
    width: 200px;
  }

  .auth-layout .form {
    width: 100%;
    height: 100%;
    display: grid;
    place-items: center;
  }
}

@media screen and (max-width: 520px) {
  .banner {
    display: none;
  }

  .auth-layout .form {
    padding: 20px;
    background: rgb(110, 180, 255);
    background: linear-gradient(
      170deg,
      rgba(110, 180, 255, 1) 0%,
      var(--color-5) 100%,
      var(--color-5) 100%
    );
  }

  .auth-layout .form .login-container {
    width: 100%;
  }
}

@media screen and (max-width: 330px) {
  .login-container h1 {
    font-size: 15px !important;
  }
}
</style>
