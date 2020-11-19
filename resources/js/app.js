import React from 'react';
import ReactDOM from 'react-dom';
import {BrowserRouter as Router} from 'react-router-dom';

import {AuthProvider} from "./contexts/AuthContext";
import MainNavbar from './components/MainNavbar/MainNavbar';
import { Routes } from './routes';

const App = () => {
    return (
        <AuthProvider>
            <Router>
                <MainNavbar/>
                <Routes/>
            </Router>
        </AuthProvider>
    )
}

ReactDOM.render(<App />,document.getElementById('app'));