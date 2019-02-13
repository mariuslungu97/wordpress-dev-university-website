import $ from 'jquery';

class MyNotes {
    constructor()  {
        this.events();
    }

    events() {
        $("#my-notes").on('click', ".delete-note",  this.deleteNote);
        $("#my-notes").on('click', '.edit-note', this.editNote.bind(this));
        $("#my-notes").on('click', '.update-note',  this.updateNote.bind(this));
        $(".submit-note").on('click', this.createNote.bind(this));
    }

    deleteNote(event) {

        const note = $(event.target).parents('li');

        $.ajax({
            beforeSend : (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce)
            },
            url : universityData.root_url + '/wp-json/wp/v2/note/' + note.data('id'),
            type : 'DELETE',
            success : (response) => {
                note.slideUp();
                console.log('Congrats');
                console.log(response);
            },
            error : (err) => {
                onsole.log('Congrats');
                console.log(err);
            }
        })
    }

    updateNote(event) {

        const note = $(event.target).parents('li');

        const updatedPost = {
            'title' : note.find('.note-title-field').val(),
            'content' : note.find('.note-body-field').val()
        }

        $.ajax({
            beforeSend : (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce)
            },
            url : universityData.root_url + '/wp-json/wp/v2/note/' + note.data('id'),
            type : 'POST',
            data : updatedPost,
            success : (response) => {

                this.makeNoteReadOnly(note);

                console.log('Congrats');
                console.log(response);
            },
            error : (err) => {
                onsole.log('Congrats');
                console.log(err);
            }
        })
    }

    createNote() {

        const createdPost = {
            'title' : $('.new-note-title').val(),
            'content' : $('.new-note-body').val(),
            'status' : 'publish'
        }

        $.ajax({
            beforeSend : (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce)
            },
            url : universityData.root_url + '/wp-json/wp/v2/note/',
            type : 'POST',
            data : createdPost,
            success : (response) => {

                $('.new-note-title, .new-note-body').val('');
                $(`
                <li data-id="${response.id}"> 
                    <input readonly class="note-title-field" type="text" value="${response.title.raw}">
                    <span class="edit-note"><i class="fas fa-pencil-alt" aria-hidden="true">Edit</i></span>
                    <span class="delete-note"><i class="fas fa-trash-alt" aria-hidden="true">Delete</i></span>
                    <textarea  readonly class="note-body-field" rows="3">${response.content.raw}</textarea>
                    <span class="update-note btn btn--blue btn--small"><i class="fas fa-arrow-right" aria-hidden="true"></i>Save</span>
                </li>
                `).prependTo('#my-notes').hide().slideDown();

                console.log('Congrats');
                console.log(response);
            },
            error : (err) => {
                onsole.log('Congrats');
                console.log(err);
            }
        })
    }

    editNote(event) {
        const note = $(event.target).parents('li');
        if(note.data('state') == 'editable') {
            this.makeNoteReadOnly(note);
        } else {
            this.makeNoteEditable(note);
        }
    }

    makeNoteEditable(note) {
        note.find('.edit-note').html('<i class="fas fa-times" aria-hidden="true">Cancel</i>')
        note.find(".note-title-field, .note-body-field").removeAttr('readonly').addClass('note-active-field');
        note.find('.update-note').addClass("update-note--visible");

        note.data('state', 'editable');
    }
    
    makeNoteReadOnly(note) {
        note.find('.edit-note').html('<i class="fas fa-pencil-alt" aria-hidden="true">Edit</i>')
        note.find(".note-title-field, .note-body-field").attr('readonly', 'readonly').removeClass('note-active-field');
        note.find('.update-note').removeClass("update-note--visible");

        note.data('state', 'cancel');

    }


};

export default MyNotes; 