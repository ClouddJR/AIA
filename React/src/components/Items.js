import React from "react"

import Item from "./Item"
import Form from "./Form"

import "../styles/Items.css"
import items from "../itemsData"

import { sortByAuthorAscending, sortByAuthorDescending, sortByRatingAscending, sortByRatingDescending, sortByTitleAscending, sortByTitleDescending } from "./sorting"

class Items extends React.Component {
    constructor() {
        super()
        this.state = {
            books: items,
            sortFunction: sortByTitleAscending,
            queryString: "",
            isAdding: false
        }
    }

    handleInputChange = (e) => {
        const queryString = e.target.value.toLowerCase()
        this.setState({ queryString: queryString })
    }

    handleSortingChange = (e) => {
        let value = e.target.value
        let sortingFunction
        switch (value) {
            case "title-ascending": sortingFunction = sortByTitleAscending; break;
            case "title-descending": sortingFunction = sortByTitleDescending; break;
            case "author-name-ascending": sortingFunction = sortByAuthorAscending; break;
            case "author-name-decending": sortingFunction = sortByAuthorDescending; break;
            case "rating-ascending": sortingFunction = sortByRatingAscending; break;
            case "rating-descending": sortingFunction = sortByRatingDescending; break;
            default: sortingFunction = sortByTitleAscending; break;
        }
        this.setState({ sortFunction: sortingFunction })
    }

    delete = (itemId) => {
        this.setState(prevState => {
            let booksCopy = prevState.books.slice()
            return {
                books: booksCopy.filter(book => book.id !== itemId),
                sortFunction: prevState.sortFunction,
                queryString: prevState.queryString,
                isAdding: prevState.isAdding
            }
        })
    }

    edit = (itemId, newRating) => {
        this.setState(prevState => {
            let booksCopy = prevState.books.slice()
            return {
                books: booksCopy.map(book => {
                    if (book.id === itemId) { book.rating = newRating }
                    return book
                }),
                sortFunction: prevState.sortFunction,
                queryString: prevState.queryString,
                isAdding: prevState.isAdding
            }
        })
    }

    handleNewItemButtonClick = () => {
        this.setState({ isAdding: true })
    }

    saveNewItem = (book) => {
        let newId = Math.max.apply(Math, this.state.books.map(function(book) { return book.id; })) + 1
        book.id = newId
        console.log(book)
        this.setState(prevState => {
            let booksCopy = prevState.books.slice()
            booksCopy.push(book)
            return {
                books: booksCopy,
                sortFunction: prevState.sortFunction,
                queryString: prevState.queryString,
                isAdding: false
            }
        })
    }

    cancelAdding = () => {
        this.setState({ isAdding: false })
    }

    render() {
        const filteredAndSortedBooks = this.state.books.filter(book => {
            return book.title.toLowerCase().includes(this.state.queryString) || book.author.toLowerCase().includes(this.state.queryString)
        }).sort(this.state.sortFunction)

        const books = filteredAndSortedBooks.map(book => {
            return <Item book={book} edit={this.edit} delete={this.delete} key={book.id} />
        })

        return (
            <div id="content">
                <input type="text" id="input-search" placeholder="Search for a book..." onChange={(e) => { this.handleInputChange(e) }} />

                <div>
                    <span>Sort by </span>
                    <select id="sorting-options" onChange={(e) => { this.handleSortingChange(e) }}>
                        <option value="title-ascending">Title ascending</option>
                        <option value="title-descending">Title descending</option>
                        <option value="author-name-ascending">Author's name ascending</option>
                        <option value="author-name-decending">Author's name descending</option>
                        <option value="rating-ascending">Rating ascending</option>
                        <option value="rating-descending">Rating descending</option>
                    </select>
                </div>

                <div>
                    {
                        !this.state.isAdding ?
                            <button id="button-add-book" onClick={this.handleNewItemButtonClick}>Add a new book</button>
                            :
                            null
                    }
                </div>

                {
                    this.state.isAdding ?
                        <Form cancel={this.cancelAdding} save={this.saveNewItem} />
                        :
                        null
                }

                <table id="table-items">
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Cover</th>
                        <th>Rating</th>
                    </tr>
                    {books}
                </table>
            </div>
        )
    }
}

export default Items