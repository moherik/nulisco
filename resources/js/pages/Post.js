import Axios from "axios";
import React, { useState, useEffect } from "react";
import { useParams } from "react-router";

const Post = () => {
  const { slug } = useParams();
  const [post, setPost] = useState({});
  const [loading, setLoading] = useState(true);

  const fetchPost = async () => {
    setLoading(true);
    await Axios.get(`/api/posts/${slug}`)
      .then(({ data }) => {
        setPost(data);
      })
      .catch(err => console.error("Error fetching data, ", err))
      .finally(() => setLoading(false));
  };

  useEffect(() => {
    fetchPost();
  }, []);

  return (
    <div>
      {!loading ? (
        <>
          <div className="profile">
            <img
              src={post.user.avatar}
              width="25"
              height="25"
              alt="photo profile"
            />
            <h4>{post.user.name}</h4>
          </div>
          <h2>{post.title}</h2>
          <p>{post.body}</p>
        </>
      ) : (
        <>
          <p>Loading....</p>
        </>
      )}
    </div>
  );
};

export default Post;
