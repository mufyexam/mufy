import cookies from "js-cookie";

export default function authGuard(router) {
  return router.beforeEach((to, from, next) => {
    const userId = cookies.get("user-id");

    if (to.meta.authGuard === false) {
      if (to.name === "Login" && userId) {
        return next({ name: "PanelView" });
      }

      return next();
    }

    if (!userId) {
      return next({ name: "Login" });
    }

    return next();
  });
}
