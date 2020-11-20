import Axios from "axios";
import React, { useState, useEffect } from "react";
import { useParams } from "react-router";
import HTMLReactParser from "html-react-parser";

import "./styles.css";
import Loading from "../../Loading/Loading";

const PostDetail = () => {
  const DEFAULT_IMAGE = "https://picsum.photos/seed/picsum/200/300?random=1";

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
      {loading ? (
        <Loading />
      ) : (
        <>
          <div className="container">
            <div className="col-md-8 mx-auto">
              <div className="mt-4">
                <h2 className="title">{post.title}</h2>
                <div className="profile mt-4 mb-2">
                  <img
                    src={post.user.avatar}
                    width="32"
                    height="32"
                    className="img rounded"
                    alt="photo profile"
                  />
                  <span className="username ml-2">{post.user.name}</span>
                  <span className="mx-2">&#8226;</span>
                  <span className="date">{post.created_at}</span>
                </div>
                {post.subtitle && (
                  <p className="subtitle mt-4 mb-0">{post.subtitle}</p>
                )}
              </div>
            </div>
          </div>

          <img src={DEFAULT_IMAGE} className="img image-cover my-4" />

          <div className="container">
            <div className="col-md-8 mx-auto">
              <div>{HTMLReactParser(post.body)}</div>
            </div>
          </div>
        </>
      )}
    </div>
  );
};

export default PostDetail;
