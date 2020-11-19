import React from 'react';
import {Switch, Route} from 'react-router-dom';

import PrivateRoute from './components/PrivateRoute';

import Home from './pages/Home/Home';
import Following from './pages/Following';  
import Post from './pages/Post';
import PostForm from './pages/PostForm/PostForm';
import Login from './pages/Auth/Login';
import LoginGoogleCallback from './pages/Auth/LoginGoogleCallback';

export const Routes = () => {
    return (
        <Switch>
            <Route exact path="/" component={Home}/>

            <Route path="/login/google/callback" component={LoginGoogleCallback}/>
            <Route path="/login" component={Login}/>

            <Route exact path="/:userid/:slug" component={Post}/>
            <PrivateRoute path="/following" component={Following}/>
            <PrivateRoute path="/create" component={PostForm}/>
            <PrivateRoute exact path="/:userid/:slug/edit" component={() => <PostForm editMode={true}/>} />
        </Switch>
    )
}
