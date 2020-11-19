import Axios from "axios";
import React, { useEffect, useState } from "react";
import PostList from "../../components/PostList/PostList";

import "./styles.css";

const Home = () => {
    const [post, setPost] = useState([]);

    const fetchPost = async () => {
        await Axios.get('/api/posts').then(({data}) => {
            setPost(data);
        }).catch((err) => console.error("error fetch data, ", err))
    }

    useEffect(() => {
        let isMounted = true;
        fetchPost();
        return () => {isMounted = false}
    }, []);

    return (
        <div className="container mt-4">
            <div className="row">
                <div className="col-md-8 col-sm-12 mx-auto">
                    {post.map(post => (
                        <PostList key={post.id} item={post}/>
                    ))}
                </div>
            </div>
        </div>
    )
}

export default Home