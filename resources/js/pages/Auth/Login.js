import React, { useEffect, useState } from "react";
import { axios } from "../../utils/axios";

import "./styles.css";

const Login = () => {
  const [googleAuthUrl, setGoogleAuthUrl] = useState(null);
  let isMounted = true;

  const fetchGoogleAuthUrl = async () => {
    await axios
      .get("/api/login/google", {
        headers: new Headers({ accept: "application/json" })
      })
      .then(({ data }) => {
        if (isMounted) setGoogleAuthUrl(data.url);
      })
      .catch(err => console.error(err));
  };

  useEffect(() => {
    fetchGoogleAuthUrl();

    return () => {
      isMounted = false;
    };
  });

  return (
    <div className="login-card card">
      <div className="card-body">
        {googleAuthUrl && (
          <>
            <h4>Login</h4>
            <p>Login with your google account</p>
            <hr />
            <a href={googleAuthUrl} className="btn btn-primary d-block">
              Login dengan Google
            </a>
          </>
        )}
      </div>
    </div>
  );
};

export default Login;
