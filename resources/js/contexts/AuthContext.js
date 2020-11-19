import React, { useEffect, useState } from 'react';
import {axios} from "../utils/axios";

export const AuthContext = React.createContext();

export const AuthProvider = ({children}) => {
    const initialState = {
        user: null,
        saving: false,
    };
    
    const reducer = (state, action) => {
        switch (action.type) {
            case "SET_USER":
                return {
                    ...state,
                    user: action.payload.user,
                }

            case "TOGGLE_SAVE":
                return {
                    ...state,
                    saving: !state.saving
                }
    
            default:
                return state;
        }
    }

    const [state, dispatch] = React.useReducer(reducer, initialState);
    
    useEffect(() => {
        const getUser = () => {
            axios.get("/api/user").then(({data}) => {
                dispatch({type: "SET_USER", payload: {user: data}})
            })
        }
            
        getUser();
    }, [])

    return (
        <AuthContext.Provider value={{state, dispatch}}>
            {children}
        </AuthContext.Provider>
    )
}