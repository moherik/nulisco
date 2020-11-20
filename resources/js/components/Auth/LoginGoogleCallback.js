import React, { useEffect, useState, useContext } from "react";
import { useHistory } from "react-router-dom";
import { axios } from "../../utils/axios";

import { AuthContext } from "../../contexts/AuthContext";
import Loading from "../Loading/Loading";

const LoginGoogleCallback = props => {
  let isMounted = true;
  let history = useHistory();
  const { dispatch } = useContext(AuthContext);

  const fetchCallbackData = async () => {
    await axios.get("/sanctum/csrf-cookie").then(() => {
      axios
        .get(`/api/login/google/callback${props.location.search}`, {
          headers: new Headers({ accept: "application/json" })
        })
        .then(({ data }) => {
          if (isMounted) {
            dispatch({ type: "SET_USER", payload: { user: data } });
            history.push("/");
          }
        })
        .catch(err => console.error(err));
    });
  };

  useEffect(() => {
    fetchCallbackData();

    return () => {
      isMounted = false;
    };
  }, []);

  return <Loading />;
};

export default LoginGoogleCallback;
