import React, {useContext, useEffect, useState} from "react";
import {Route, Redirect} from "react-router-dom";

import { axios } from "../utils/axios";

const PrivateRoute = ({component: Component, login, ...rest}) => {
    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const getUser = async () => {
            await axios.get("/api/user").then(({data}) => {
                setUser(data)
                setLoading(false)
            })
        }

        getUser()
    }, [])
   
    return (
        <Route {...rest} render={(props) => {
            return loading ? (
                <p>Loading...</p>
            ) : (
                user && user !== null ? (
                    <Component {...props}/>
                ) : (
                    <Redirect to={{pathname: "/login", state: {from: props.location}}}/>
                )    
            )
        }}/>
    )    
}

export default PrivateRoute