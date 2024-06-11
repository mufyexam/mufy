import Vue from "vue";
import VueRouter from "vue-router";

import authGuard from "./middlewares/auth-guard";

import AuthLayout from "../layouts/AuthLayout.vue";
import LoginView from "../views/auth/LoginView.vue";

import PanelLayout from "../layouts/PanelLayout.vue";
import PanelView from "../views/PanelView.vue";

import KurumView from "../views/fizikselTanimlamalar/KurumView.vue";
import KampusView from "../views/fizikselTanimlamalar/KampusView.vue";
import BinaView from "../views/fizikselTanimlamalar/BinaView.vue";
import KatView from "../views/fizikselTanimlamalar/KatView.vue";
import SalonView from "../views/fizikselTanimlamalar/SalonView.vue";

import DersView from "../views/akademikTanimlamalar/DersView.vue";
import PersonelView from "../views/akademikTanimlamalar/PersonelView.vue";
import AdayView from "../views/akademikTanimlamalar/AdayView.vue";

import SinavOlusturView from "../views/sinav/SinavOlusturView.vue";
import SinavBaslatView from "../views/sinav/SinavBaslatView.vue";

import RaporLayout from "../layouts/RaporLayout.vue";
import SinavlarView from "../views/rapor/SinavlarView.vue";
import SinavBelgesiView from "../views/rapor/SinavBelgesiView.vue";

Vue.use(VueRouter);

const routes = [
  {
    path: "/auth",
    name: "Auth",
    component: AuthLayout,
    children: [
      {
        path: "login",
        name: "Login",
        component: LoginView,
        meta: {
          authGuard: false,
        },
      },
    ],
  },
  {
    path: "/panel",
    component: PanelLayout,
    children: [
      {
        path: "",
        name: "PanelView",
        component: PanelView,
      },
      {
        path: "fiziksel-tanimlamalar/kurum",
        name: "Kurum",
        component: KurumView,
      },
      {
        path: "fiziksel-tanimlamalar/kampus",
        name: "Kampus",
        component: KampusView,
      },
      {
        path: "fiziksel-tanimlamalar/bina",
        name: "Bina",
        component: BinaView,
      },
      {
        path: "fiziksel-tanimlamalar/kat",
        name: "Kat",
        component: KatView,
      },
      {
        path: "fiziksel-tanimlamalar/salon",
        name: "Salon",
        component: SalonView,
      },
      {
        path: "akademik-tanimlamalar/ders",
        name: "Ders",
        component: DersView,
      },
      {
        path: "akademik-tanimlamalar/personel",
        name: "Personel",
        component: PersonelView,
      },
      {
        path: "akademik-tanimlamalar/aday",
        name: "Aday",
        component: AdayView,
      },
      {
        path: "sinav/sinav-olustur",
        name: "SinavOlustur",
        component: SinavOlusturView,
      },
      {
        path: "sinav/sinav-baslat/:examId",
        name: "SinavBaslat",
        component: SinavBaslatView,
      },
    ],
  },
  {
    path: "/rapor",
    component: RaporLayout,
    children: [
      {
        path: "",
        name: "RaporlarView",
        component: SinavlarView,
      },
      {
        path: "sinav-belgesi/:examId",
        name: "SinavBelgesi",
        component: SinavBelgesiView,
      },
    ],
  },
  {
    path: "/:catchAll(.*)",
    redirect: { name: "PanelView" },
  },
];

const router = new VueRouter({
  mode: "hash",
  base: process.env.BASE_URL,
  routes,
});

authGuard(router);

export default router;
