import React, {useContext} from 'react';
import {Link, useHistory} from "react-router-dom";
import {Icon} from "@iconify/react";

import user from "@iconify-icons/uil/chat-bubble-user";
import plus from "@iconify-icons/uil/plus-circle";
import userCircle from "@iconify-icons/uil/user-circle";
import signOut from "@iconify-icons/uil/sign-out-alt";
import post from "@iconify-icons/uil/message";

import { AuthContext } from '../../contexts/AuthContext';
import { axios } from '../../utils/axios';

import "./styles.css";

const MainNavbar = () => {
  let history = useHistory();
  const {state, dispatch} = useContext(AuthContext)

  const handleSignOut = async (e) => {
    e.preventDefault();

    await axios.get("/api/logout").then(() => {
      dispatch({type: "SET_USER", payload: {user: null}});
    }).finally(() => history.replace("/"))
  }

  const LinkItemLabel = ({icon, label}) => {
    return (
      <>
        {icon && (<Icon icon={icon} width="24" className="text-dark"/>)}
        {label && (<span className="ml-2 text-dark">{label}</span>)}
      </>
    )
  }
  
  const LinkItem = (props) => {
    return (
      <li className="nav-item ml-auto">
        {!props.to && props.href ? (
          <a href="#" {...props} className="nav-link d-flex flex-row">
            <LinkItemLabel icon={props.icon} label={props.label}/>
          </a>
        ) : (
          <Link to={props.to} className="nav-link d-flex flex-row">
            <LinkItemLabel icon={props.icon} label={props.label}/>
          </Link>
        )}
      </li>
    )
  }

  return (
    <nav id="main-navbar" className="navbar navbar-expand-lg navbar-light bg-white">
      <div className="container">
        <Link to="/" className="navbar-brand text-dark">
          <h4 className="m-0">nulis.co</h4>
        </Link>

        <button className="navbar-toggler" type="button">
          <span className="navbar-toggler-icon"></span>
        </button>

        {state.saving && (
          <div className="text-dark">
            <span>Saving...</span>
          </div>
        )}

        <div className="collapse navbar-collapse">
          <ul className="navbar-nav ml-auto">
              {!state.user ? (<LinkItem to="/login" icon={userCircle} />) : (
                <>
                  <LinkItem to="/following" icon={user}/>
                  <LinkItem to="/create" icon={plus}/>
                  <LinkItem href="#" onClick={handleSignOut} icon={signOut} />
                </>
              )}
          </ul>
        </div>
      </div>
    </nav>
  );
}

export default MainNavbar;