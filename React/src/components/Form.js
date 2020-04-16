import React from "react"

import "../styles/Form.css"

class Form extends React.Component {

    constructor() {
        super()
        this.state = {
            title: "",
            author: "",
            img: "",
            rating: 10
        }
    }

    handleChange = (event) => {
        this.setState({
            [event.target.name]: event.target.value
        })
    }

    changeRating = (e) => {
        let regex = new RegExp("^([1-9]|10)$");
        let rex = regex.test(e.target.value);
        if (rex) {
            this.setState({ rating: e.target.value })
        }
    }

    saveNewItem = () => {
        const book = {
            "title" : this.state.title,
            "author" : this.state.author,
            "img" : this.state.imageURL,
            "rating" : this.state.rating,
        }
        this.props.save(book)
    }

    cancelAdding = () => {
        this.props.cancel()
    }

    render() {
        return (
            <div>
                <form id="form-new-book">
                    <input type="text" name="title" placeholder="Book's title" onChange={this.handleChange} />
                    <input type="text" name="author" placeholder="Author" onChange={this.handleChange} />
                    <input type="text" name="imageURL" placeholder="Book's cover URL" onChange={this.handleChange} />
                    <label for="rating">Rating</label>
                    <input type="number" name="rating" step="1" value={this.state.rating} onChange={this.changeRating} />
                </form>
                <div>
                    <button id="form-button-add" onClick={this.saveNewItem}>Add</button>
                    <button id="form-button-cancel" onClick={this.cancelAdding}>Cancel</button>
                </div>
            </div>
        )
    }
}

export default Form