const mongoose = require('mongoose');

const Schema = mongoose.Schema;

const genre = new Schema({
    title: {
        type: String,
        required: true
    },
    books: [{
        book: String,
        id: {
            type: Schema.Types.ObjectId, 
            ref: 'book'
        }
    }]
})

module.exports = mongoose.model("genre", genre)