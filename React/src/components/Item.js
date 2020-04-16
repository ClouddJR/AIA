import React from "react"

import "../styles/Item.css"

class Item extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            isEditingRating: false,
            newRating: props.book.rating
        }
    }

    toggleEdit = () => {
        this.setState({ isEditingRating: true })
    }

    changeRating = (e) => {
        let regex = new RegExp("^([1-9]|10)$");
        let rex = regex.test(e.target.value);
        if (rex) {
            this.setState({ newRating: e.target.value })
        }
    }

    saveRatingChange = () => {
        this.props.edit(this.props.book.id, this.state.newRating)
        this.setState({ isEditingRating: false })
    }

    render() {
        return (
            <tr>
                <td>{this.props.book.title}</td>
                <td>{this.props.book.author}</td>
                <td>
                    <img src={this.props.book.img} alt="book-cover" />
                </td>
                <td>
                    {
                        this.state.isEditingRating ?
                            <input type="number" value={this.state.newRating} step="1" onChange={this.changeRating} />
                            :
                            this.props.book.rating + "/10"
                    }

                </td>
                <td>
                    {
                        this.state.isEditingRating ?
                            <button id="save" onClick={() => this.saveRatingChange()}> Save</button>
                            :
                            <button id="edit" onClick={() => this.toggleEdit()}>Edit</button>
                    }
                    <button id="delete" onClick={() => this.props.delete(this.props.book.id)}>Delete</button>
                </td>
            </tr >
        )
    }
}

export default Item