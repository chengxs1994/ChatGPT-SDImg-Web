import React, { useState } from "react";
import { Modal } from "antd";
import { Link } from "react-router-dom";
import { Path } from "../constant";
import styles from "./login.module.scss";
import { accountReLogin } from "@/app/requests";
import Locale, { setItem } from "../locales";

export function ReLoginModal(props: { onClose: () => void }) {
  const [isModalOpen, setIsModalOpen] = useState(true);

  let [inputAccountValue, setInputAccountValue] = useState("");
  const handleInputAccountChange = (
    event: React.ChangeEvent<HTMLInputElement>,
  ) => {
    setInputAccountValue(event.target.value);
  };

  let [inputPasswordValue, setInputPasswordValue] = useState("");
  const handleInputPasswordChange = (
    event: React.ChangeEvent<HTMLInputElement>,
  ) => {
    setInputPasswordValue(event.target.value);
  };

  async function accountLoginSubmit() {
    await accountReLogin(inputAccountValue, inputPasswordValue)
      .then((response) => response.json())
      .then((data) => {
        if (data.status != 200) {
          window.alert(data.msg);
          return false;
        }
        setItem("USER_KEY", data.data.token);
        props.onClose();
        return true;
      })
      .catch((error) => console.error(error));
  }

  return (
    <>
      <Modal
        title="登录/注册"
        open={isModalOpen}
        onOk={props.onClose}
        onCancel={props.onClose}
        cancelText="取消"
        okText="确定"
        footer={null}
        closable={false}
        maskClosable={false}
      >
        <div className={styles["settings"]}>
          <div className={styles["settings-item"]}>
            <label>账号：</label>
            <input
              style={{ width: "100%", maxWidth: "100%" }}
              type="text"
              value={inputAccountValue}
              onChange={handleInputAccountChange}
            />
          </div>
          <div className={styles["settings-item"]}>
            <label>密码:</label>
            <input
              style={{ width: "100%", maxWidth: "100%" }}
              type="password"
              value={inputPasswordValue}
              onChange={handleInputPasswordChange}
            />
          </div>

          <div className={styles["settings-login"]}>
            <button
              onClick={accountLoginSubmit}
              style={{ width: "90%" }}
              className={styles["btn"]}
            >
              登录/注册
            </button>
          </div>
        </div>
      </Modal>
    </>
  );
}
