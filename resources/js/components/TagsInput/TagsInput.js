import React,{ useEffect, useState } from "react"

import "./styles.css"

const Tag = props => <span className="tag" {...props} />
const Delete = props => <button className="delete" {...props} />

const TagsInput = (props) => {
    const [newTag, setNewTag] = useState([])
    const [tag, setTag] = useState([]);
      
    const handleChange = (e) => {
        if(e.target.value != "") setTag(props.value.split(","));
    }

    const handleKeyDown = (e) => {
        if (e.keyCode === 13 && e.target.value !== '')  {
            let newTag = newTag.trim()

            if (props.value.indexOf(newTag) === -1) {
                props.value.push(newTag)
                setNewTag("")
            }

            e.target.value = ""
        }
    }
  
    const handleRemoveTag = (e) => {
        let tag = e.target.parentNode.textContent.trim()
        let index = props.value.indexOf(tag)
        this.props.value.splice(index, 1)
        setNewTag("")
    }

    useEffect(() => {
        if(props.value != "") setTag(props.value.split(","));
    }, [])
  
    return (
        <div>
            <div className="tags-input">
                {tag.map((tag, index) => (
                    <Tag key={index}>{tag}<Delete onClick={handleRemoveTag} /></Tag>
                ))}
                <input type="text" onChange={handleChange} onKeyDown={handleKeyDown} />
            </div>
            <span className="help">hit 'Enter' to add new tags</span>
        </div>
    )
}

export default TagsInput