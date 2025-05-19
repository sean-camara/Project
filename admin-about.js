document.addEventListener("DOMContentLoaded", () => {

    // Helper function to send POST AJAX requests
    function postData(data) {
        return fetch("admin-about-action.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams(data)
        }).then(res => res.json());
    }

    // --- CONTACTS ---

    const contactsTable = document.querySelector("#contacts-table tbody");
    const addContactBtn = document.getElementById("add-contact-btn");

    function createContactRow(contact) {
        const tr = document.createElement("tr");
        tr.dataset.id = contact.id;
        tr.innerHTML = `
            <td class="contact-type">${contact.type}</td>
            <td class="contact-value">${contact.value}</td>
            <td>
                <button class="btn-edit contact-edit-btn"><i class="fa fa-pen"></i> Edit</button>
                <button class="btn-delete contact-delete-btn"><i class="fa fa-trash"></i> Delete</button>
            </td>
        `;
        return tr;
    }

    // Add Contact
    addContactBtn.addEventListener("click", () => {
        if (document.querySelector("#contacts-section .inline-form")) return; // prevent multiple forms

        const form = document.createElement("div");
        form.className = "inline-form";
        form.innerHTML = `
            <input type="text" name="type" placeholder="Contact Type (e.g. Phone, Email)" required />
            <input type="text" name="value" placeholder="Contact Value" required />
            <button class="btn-add">Add</button>
            <button class="btn-delete cancel-btn">Cancel</button>
        `;
        contactsTable.parentElement.insertBefore(form, contactsTable);

        form.querySelector(".btn-add").addEventListener("click", async () => {
            const type = form.querySelector('input[name="type"]').value.trim();
            const value = form.querySelector('input[name="value"]').value.trim();
            if (!type || !value) return alert("Please fill in all fields");

            const res = await postData({ action: "add-contact", contact_type: type, contact_value: value });
            if (res.success) {
                contactsTable.appendChild(createContactRow({ id: res.id, type, value }));
                form.remove();
            } else alert(res.error || "Failed to add contact");
        });

        form.querySelector(".cancel-btn").addEventListener("click", () => {
            form.remove();
        });
    });

    // Edit/Delete Contacts (event delegation)
    contactsTable.addEventListener("click", async (e) => {
        if (e.target.closest(".contact-edit-btn")) {
            const tr = e.target.closest("tr");
            if (document.querySelector("#contacts-section .inline-form")) return alert("Finish editing first");
            const currentType = tr.querySelector(".contact-type").textContent;
            const currentValue = tr.querySelector(".contact-value").textContent;

            // Replace row content with inputs
            tr.innerHTML = `
                <td><input type="text" name="type" value="${currentType}" required /></td>
                <td><input type="text" name="value" value="${currentValue}" required /></td>
                <td>
                    <button class="btn-add save-btn">Save</button>
                    <button class="btn-delete cancel-btn">Cancel</button>
                </td>
            `;

            tr.querySelector(".save-btn").addEventListener("click", async () => {
                const newType = tr.querySelector('input[name="type"]').value.trim();
                const newValue = tr.querySelector('input[name="value"]').value.trim();
                if (!newType || !newValue) return alert("Please fill in all fields");

                const res = await postData({ action: "edit-contact", id: tr.dataset.id, contact_type: newType, contact_value: newValue });
                if (res.success) {
                    tr.innerHTML = `
                        <td class="contact-type">${newType}</td>
                        <td class="contact-value">${newValue}</td>
                        <td>
                            <button class="btn-edit contact-edit-btn"><i class="fa fa-pen"></i> Edit</button>
                            <button class="btn-delete contact-delete-btn"><i class="fa fa-trash"></i> Delete</button>
                        </td>
                    `;
                } else alert(res.error || "Failed to save");
            });

            tr.querySelector(".cancel-btn").addEventListener("click", () => {
                // Restore original content
                tr.innerHTML = `
                    <td class="contact-type">${currentType}</td>
                    <td class="contact-value">${currentValue}</td>
                    <td>
                        <button class="btn-edit contact-edit-btn"><i class="fa fa-pen"></i> Edit</button>
                        <button class="btn-delete contact-delete-btn"><i class="fa fa-trash"></i> Delete</button>
                    </td>
                `;
            });

        } else if (e.target.closest(".contact-delete-btn")) {
            const tr = e.target.closest("tr");
            if (!confirm("Delete this contact?")) return;
            const res = await postData({ action: "delete-contact", id: tr.dataset.id });
            if (res.success) {
                tr.remove();
            } else alert(res.error || "Failed to delete");
        }
    });

    // --- FAQS ---

    const faqTable = document.querySelector("#faq-table tbody");
    const addFaqBtn = document.getElementById("add-faq-btn");

    function createFaqRow(faq) {
        const tr = document.createElement("tr");
        tr.dataset.id = faq.id;
        tr.innerHTML = `
            <td class="faq-question">${faq.question}</td>
            <td class="faq-answer">${faq.answer}</td>
            <td>
                <button class="btn-edit faq-edit-btn"><i class="fa fa-pen"></i> Edit</button>
                <button class="btn-delete faq-delete-btn"><i class="fa fa-trash"></i> Delete</button>
            </td>
        `;
        return tr;
    }

    addFaqBtn.addEventListener("click", () => {
        if (document.querySelector("#faq-section .inline-form")) return;

        const form = document.createElement("div");
        form.className = "inline-form";
        form.innerHTML = `
            <input type="text" name="question" placeholder="Question" required />
            <input type="text" name="answer" placeholder="Answer" required />
            <button class="btn-add">Add</button>
            <button class="btn-delete cancel-btn">Cancel</button>
        `;
        faqTable.parentElement.insertBefore(form, faqTable);

        form.querySelector(".btn-add").addEventListener("click", async () => {
            const question = form.querySelector('input[name="question"]').value.trim();
            const answer = form.querySelector('input[name="answer"]').value.trim();
            if (!question || !answer) return alert("Please fill in all fields");

            const res = await postData({ action: "add-faq", question, answer });
            if (res.success) {
                faqTable.appendChild(createFaqRow({ id: res.id, question, answer }));
                form.remove();
            } else alert(res.error || "Failed to add FAQ");
        });

        form.querySelector(".cancel-btn").addEventListener("click", () => {
            form.remove();
        });
    });

    faqTable.addEventListener("click", async (e) => {
        if (e.target.closest(".faq-edit-btn")) {
            const tr = e.target.closest("tr");
            if (document.querySelector("#faq-section .inline-form")) return alert("Finish editing first");
            const currentQ = tr.querySelector(".faq-question").textContent;
            const currentA = tr.querySelector(".faq-answer").textContent;

            tr.innerHTML = `
                <td><input type="text" name="question" value="${currentQ}" required /></td>
                <td><input type="text" name="answer" value="${currentA}" required /></td>
                <td>
                    <button class="btn-add save-btn">Save</button>
                    <button class="btn-delete cancel-btn">Cancel</button>
                </td>
            `;

            tr.querySelector(".save-btn").addEventListener("click", async () => {
                const newQ = tr.querySelector('input[name="question"]').value.trim();
                const newA = tr.querySelector('input[name="answer"]').value.trim();
                if (!newQ || !newA) return alert("Please fill in all fields");

                const res = await postData({ action: "edit-faq", id: tr.dataset.id, question: newQ, answer: newA });
                if (res.success) {
                    tr.innerHTML = `
                        <td class="faq-question">${newQ}</td>
                        <td class="faq-answer">${newA}</td>
                        <td>
                            <button class="btn-edit faq-edit-btn"><i class="fa fa-pen"></i> Edit</button>
                            <button class="btn-delete faq-delete-btn"><i class="fa fa-trash"></i> Delete</button>
                        </td>
                    `;
                } else alert(res.error || "Failed to save");
            });

            tr.querySelector(".cancel-btn").addEventListener("click", () => {
                tr.innerHTML = `
                    <td class="faq-question">${currentQ}</td>
                    <td class="faq-answer">${currentA}</td>
                    <td>
                        <button class="btn-edit faq-edit-btn"><i class="fa fa-pen"></i> Edit</button>
                        <button class="btn-delete faq-delete-btn"><i class="fa fa-trash"></i> Delete</button>
                    </td>
                `;
            });

        } else if (e.target.closest(".faq-delete-btn")) {
            const tr = e.target.closest("tr");
            if (!confirm("Delete this FAQ?")) return;
            const res = await postData({ action: "delete-faq", id: tr.dataset.id });
            if (res.success) {
                tr.remove();
            } else alert(res.error || "Failed to delete");
        }
    });
});
