import Axios from "axios";
import React, { useEffect, useState } from "react";
import PostList from "../../components/Post/List/PostList";
import { axios } from "../../utils/axios";

import "./styles.css";

const Home = () => {
  const [post, setPost] = useState([]);

  const fetchPost = async () => {
    await Axios.get("/api/posts")
      .then(({ data }) => setPost(data))
      .catch(err => console.error("error fetch data, ", err));
  };

  const handleDelete = async (e, id) => {
    e.preventDefault();

    await axios
      .delete(`/api/posts/${id}`)
      .then(_res => setPost(post.filter(item => item.id !== id)))
      .catch(err => console.error(err));
  };

  useEffect(() => {
    let isMounted = true;
    fetchPost();
    return () => {
      isMounted = false;
    };
  }, []);

  return (
    <div className="container mt-4">
      <div className="row">
        <div className="col-md-8 col-sm-12 mx-auto">
          {post.map(post => (
            <PostList
              key={post.id}
              item={post}
              deletePost={e => handleDelete(e, post.id)}
            />
          ))}
        </div>
      </div>
    </div>
  );
};

export default Home;
