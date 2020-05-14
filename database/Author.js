const mongoose = require('mongoose');

const Schema = mongoose.Schema;

const author = new Schema({
    title: {
        type: String,
        required: true
    },
    books: [{
        type: Schema.Types.ObjectId, 
        ref: 'book'
    }]
})


module.exports = mongoose.model("author", author)