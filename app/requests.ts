import { getItem } from "./locales";
import { getServerSideConfig } from "@/app/config/server";

const serverConfig = getServerSideConfig();
const USER_HOST = serverConfig.reqUrl;

export async function accountReLogin(
  inputAccountValue: string,
  inputPasswordValue: string,
) {
  const formData = new FormData();
  formData.append("account", inputAccountValue);
  formData.append("password", inputPasswordValue);

  return fetch(USER_HOST + `/api/account-relogin`, {
    method: "POST",
    body: formData,
  });
}

export async function userInfo() {
  const formData = new FormData();

  var userKey = getItem("USER_KEY");
  if (!userKey) {
    userKey = "";
  }
  formData.append("token", userKey);

  return fetch(USER_HOST + `/api/user-info`, {
    method: "POST",
    body: formData,
  });
}

export async function send(content: string) {
  const formData = new FormData();

  var userKey = getItem("USER_KEY");
  if (!userKey) {
    userKey = "";
  }
  formData.append("token", userKey);

  formData.append("message", content);
  return fetch(USER_HOST + `/api/send`, {
    method: "POST",
    body: formData,
  });
}
