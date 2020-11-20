import React, { useContext, useEffect, useRef, useState } from "react";
import { CKEditor } from "@ckeditor/ckeditor5-react";
import BaloonBlockEditor from "@ckeditor/ckeditor5-build-balloon-block";
import { useParams } from "react-router-dom";
import { axios } from "../../../utils/axios";

import { AuthContext } from "../../../contexts/AuthContext";

import "./styles.css";
import Loading from "../../Loading/Loading";

const PostForm = ({ editMode }) => {
  const { state, dispatch } = useContext(AuthContext);

  const [editId, setEditId] = useState("");
  const [body, setBody] = useState("");
  const [title, setTitle] = useState("");
  const [subtitle, setSubtitle] = useState("");
  const [tags, setTags] = useState([]);
  const [isSticky, setIsSticky] = useState(false);
  const [loading, setLoading] = useState(true);

  const stickyRef = useRef(null);
  const { slug } = useParams();

  const createPost = async () => {
    dispatch({ type: "TOGGLE_SAVE" });
    await axios
      .post("/api/posts", {
        title,
        subtitle,
        body,
        tags,
        status: "PUBLISH"
      })
      .then(response => console.log(response))
      .catch(err => console.error(err))
      .finally(() => dispatch({ type: "TOGGLE_SAVE" }));
  };

  const updatePost = async id => {
    if (id) {
      dispatch({ type: "TOGGLE_SAVE" });
      await axios
        .patch(`/api/posts/${id}`, {
          title,
          subtitle,
          body,
          tags
        })
        .then(response => console.log(response))
        .catch(err => console.error(err))
        .finally(() => dispatch({ type: "TOGGLE_SAVE" }));
    }
  };

  const handleSave = async () => {
    if (title && body) {
      if (!editMode) {
        createPost();
      } else {
        updatePost(editId);
      }
    }
  };

  const handleChangeTag = e => {
    const value = e.target.value;
    if (value !== "") {
      const tags = value.split(",");
      const newTag = tags.map((tag, index) => [title => tag]);
      setTags(newTag);
    }
  };

  const handleScroll = () => {
    if (stickyRef.current) {
      setIsSticky(stickyRef.current.getBoundingClientRect().top <= 0);
    }
  };

  const fetchEditData = async () => {
    if (slug) {
      await axios
        .get(`/api/posts/${slug}/edit`)
        .then(({ data }) => {
          setEditId(data.id);
          setTitle(data.title);
          setSubtitle(data.subtitle);
          setBody(data.body);
        })
        .catch(err => console.error(err))
        .finally(() => setLoading(false));
    }
  };

  useEffect(() => {
    window.addEventListener("scroll", handleScroll);

    if (editMode) {
      fetchEditData();
    }

    return () => {
      window.removeEventListener("scroll", () => handleScroll);
    };
  }, []);

  if (editMode && loading) {
    return <Loading />;
  }

  return (
    <div className="container mt-4">
      <div className="row">
        <div className="col-md-8 mx-auto">
          <div
            className={`top-section ${isSticky ? "sticky" : ""}`}
            ref={stickyRef}
          >
            <input
              type="text"
              className="input-title mr-2"
              placeholder="Your post title"
              value={title}
              onChange={e => setTitle(e.target.value)}
              tabIndex="1"
              autoFocus
            />
            <div className="ml-auto">
              <button
                type="button"
                className="btn btn-primary btn-sm"
                disabled={title === "" || body === ""}
                onClick={handleSave}
              >
                {!state.saving
                  ? !editMode
                    ? "Publish"
                    : "Update"
                  : "Saving..."}
              </button>
            </div>
          </div>
          <input
            type="text"
            className="input"
            tabIndex="2"
            placeholder="Insert subtitle"
            value={subtitle}
            onChange={e => setSubtitle(e.target.value)}
          />
          <CKEditor
            editor={BaloonBlockEditor}
            config={{
              toolbar: [
                "heading",
                "bold",
                "italic",
                "blockquote",
                "link",
                "|",
                "numberedList",
                "bulletedList",
                "|",
                "undo",
                "redo"
              ],
              placeholder: "Insert your text",
              tabIndex: 3
            }}
            data={body}
            onChange={(evt, editor) => setBody(editor.getData())}
          />
          <input
            type="text"
            className="input"
            tabIndex="2"
            placeholder="Insert tag"
            onChange={handleChangeTag}
          />
        </div>
      </div>
    </div>
  );
};

export default PostForm;
