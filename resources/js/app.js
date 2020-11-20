import React from "react";
import ReactDOM from "react-dom";
import { BrowserRouter as Router } from "react-router-dom";

import { AuthProvider } from "./contexts/AuthContext";
import Navbar from "./components/Navbar/Navbar";
import { Routes } from "./routes";

const App = () => {
  return (
    <AuthProvider>
      <Router>
        <Navbar />
        <Routes />
      </Router>
    </AuthProvider>
  );
};

ReactDOM.render(<App />, document.getElementById("app"));
