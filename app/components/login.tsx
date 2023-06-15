import { useState, useEffect } from "react";

import styles from "./login.module.scss";

import CloseIcon from "../icons/close.svg";

import { IconButton } from "./button";
import { useUpdateStore, useAccessStore, useAppConfig } from "../store";

import Locale from "../locales";
import { Path } from "../constant";
import { ErrorBoundary } from "./error";
import { Link, useNavigate } from "react-router-dom";

export function Login() {
  const navigate = useNavigate();

  let [inputEmailValue, setInputEmailValue] = useState("");
  const handleInputEmailChange = (
    event: React.ChangeEvent<HTMLInputElement>,
  ) => {
    setInputEmailValue(event.target.value);
  };

  let [inputCodeValue, setInputCodeValue] = useState("");
  const handleInputCodeChange = (
    event: React.ChangeEvent<HTMLInputElement>,
  ) => {
    setInputCodeValue(event.target.value);
  };

  const updateStore = useUpdateStore();
  const [checkingUpdate, setCheckingUpdate] = useState(false);
  const currentVersion = updateStore.version;
  const remoteId = updateStore.remoteVersion;
  const hasNewVersion = currentVersion !== remoteId;

  function checkUpdate(force = false) {
    setCheckingUpdate(true);
    updateStore.getLatestVersion(force).then(() => {
      setCheckingUpdate(false);
    });
  }

  const [loadingUsage, setLoadingUsage] = useState(false);

  function checkUsage() {
    setLoadingUsage(true);
    updateStore.updateUsage().finally(() => {
      setLoadingUsage(false);
    });
  }

  async function getCodeSubmit() {
    // await get code
  }
  // 邮箱 |
  async function emailLoginSubmit() {
    // await login
  }

  const accessStore = useAccessStore();

  const showUsage = accessStore.isAuthorized();
  useEffect(() => {
    // checks per minutes
    checkUpdate();
    showUsage && checkUsage();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  useEffect(() => {
    const keydownEvent = (e: KeyboardEvent) => {
      if (e.key === "Escape") {
        navigate(Path.Home);
      }
    };
    document.addEventListener("keydown", keydownEvent);
    return () => {
      document.removeEventListener("keydown", keydownEvent);
    };
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  return (
    <ErrorBoundary>
      <div className={styles["window-header"]}>
        <div className={styles["window-header-title"]}>
          <div className={styles["window-header-main-title"]}>登录注册</div>
          <div className={styles["window-header-sub-title"]}>登录</div>
        </div>
        <div className={styles["window-actions"]}>
          <div className={styles["window-action-button"]}>
            <IconButton
              icon={<CloseIcon />}
              onClick={() => navigate(Path.Home)}
              bordered
              title={Locale.Settings.Actions.Close}
            />
          </div>
        </div>
      </div>
      <div className={styles["settings"]}>
        <div className={styles["settings-item"]}>
          <label>邮箱：</label>
          <input
            style={{ minWidth: "200px" }}
            type="text"
            value={inputEmailValue}
            onChange={handleInputEmailChange}
          />
        </div>
        <div className={styles["settings-item"]}>
          <label>验证码:</label>
          <input
            style={{ maxWidth: "100px", marginRight: "10px" }}
            type="text"
            value={inputCodeValue}
            onChange={handleInputCodeChange}
          />
          <button onClick={getCodeSubmit} className={styles["btn"]}>
            获取验证码
          </button>
        </div>

        <div className={styles["settings-login"]}>
          <button
            onClick={emailLoginSubmit}
            style={{ width: "90%" }}
            className={styles["btn"]}
          >
            登录
          </button>
        </div>

        <Link to={Path.Register} style={{ textDecoration: "none" }}>
          <p style={{ color: "black", fontSize: "14px" }}>
            还没有账号，<a style={{ color: "#4e6ef2" }}>立即注册</a>
          </p>
        </Link>
      </div>
    </ErrorBoundary>
  );
}
