const mongoose = require('mongoose');

const Schema =  mongoose.Schema

const order = new Schema({
    books: [{
        type: Schema.Types.ObjectId, 
        ref: 'book'
    }],
    user : {
        name: {
            type: String,
            required: true
        },
        mail: {
            type: String,
            required: true
        },
        userId: {
            type: Schema.Types.ObjectId,
            required: true,
            ref: 'user'
        }
    }
})


module.exports = mongoose.model("order", order)