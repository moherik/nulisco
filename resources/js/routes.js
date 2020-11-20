import React from "react";
import { Switch, Route } from "react-router-dom";

import PrivateRoute from "./components/PrivateRoute";

import Home from "./components/Home/Home";
import Following from "./components/Following";
import PostDetail from "./components/Post/Detail/PostDetail";
import PostForm from "./components/Post/Form/PostForm";
import Login from "./components/Auth/Login";
import LoginGoogleCallback from "./components/Auth/LoginGoogleCallback";

export const Routes = () => {
  return (
    <Switch>
      <Route exact path="/" component={Home} />

      <Route path="/login/google/callback" component={LoginGoogleCallback} />
      <Route path="/login" component={Login} />

      <Route exact path="/:userid/:slug" component={PostDetail} />
      <PrivateRoute path="/following" component={Following} />
      <PrivateRoute path="/create" component={PostForm} />
      <PrivateRoute
        exact
        path="/:userid/:slug/edit"
        component={() => <PostForm editMode={true} />}
      />
    </Switch>
  );
};
