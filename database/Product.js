const mongoose = require('mongoose');

const Schema = mongoose.Schema;

const book = new Schema({
    title: {
        type: String,
        required: true
    },
    path:{
        type: String,
        required: true
    },
    author: {
        type: String,
        required: true
    },
    genre: {
        type: String,
        required: true
    },
    series: {
        type: String,
        required: true
    },
    price: {
        type: Number,
        required: true
    },
    description:{
        type: String,
        required: true
    },
    cover: {
        type: String,
        required: true
    }
})

module.exports = mongoose.model("book", book)
