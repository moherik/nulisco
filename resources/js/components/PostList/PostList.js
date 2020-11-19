import React, { useContext, useState, useEffect } from "react";
import { Link } from "react-router-dom";
import { AuthContext } from "../../contexts/AuthContext";

import "./styles.css";

const PostList = ({ item }) => {
  const { state } = useContext(AuthContext);

  const DEFAULT_IMAGE = "https://picsum.photos/seed/picsum/200/300?random=1";

  return (
    <div className="post-list mb-5">
      <div className="text">
        <div className="profile mb-2">
          <img src={item.user.avatar} className="img rounded avatar" />
          <span className="username mx-2">
            {item.user.name || item.user.username}
          </span>
        </div>
        <Link to={`/${item.user.userid}/${item.slug}`} className="title">
          {item.short_title}
        </Link>
        <div className="mt-2">
          <p>{item.desc}</p>
          <p>{item.created_at}</p>
          {state.user && state.user.id === item.user.id && (
            <>
              <Link to={`/${item.user.userid}/${item.slug}/edit`}>Edit</Link>
              <Link to="/delete" className="text-danger">
                Delete
              </Link>
            </>
          )}
        </div>
      </div>
      <div className="image text-right">
        <img
          src={item.image ? item.image : DEFAULT_IMAGE}
          className="img rounded thumbnail"
        />
      </div>
    </div>
  );
};

export default PostList;
