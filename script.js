document.addEventListener("DOMContentLoaded", function () {
  // Gestion des votes
  document.querySelectorAll(".vote-btn").forEach((button) => {
    button.addEventListener("click", async function () {
      const debateId = this.dataset.debateId;
      const voteType = this.dataset.voteType;
      const voteButtons = document.querySelectorAll(
        `.vote-btn[data-debate-id="${debateId}"]`
      );
      const messageElement = document.getElementById(
        `vote-message-${debateId}`
      );

      try {
        const response = await fetch("vote.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            debate_id: debateId,
            vote_type: voteType,
          }),
        });

        const data = await response.json();

        if (data.success) {
          // Mettre à jour les compteurs
          document.getElementById(`votes-pour-${debateId}`).textContent =
            data.votes_pour;
          document.getElementById(`votes-contre-${debateId}`).textContent =
            data.votes_contre;

          // Désactiver tous les boutons de vote
          voteButtons.forEach((btn) => {
            btn.disabled = true;
            if (btn.dataset.voteType === data.vote_type) {
              btn.classList.add("voted");
            }
          });

          // Afficher le message de remerciement
          messageElement.textContent = data.message;
        } else {
          // Si l'utilisateur a déjà voté, marquer son vote précédent
          if (data.vote_type) {
            voteButtons.forEach((btn) => {
              btn.disabled = true;
              if (btn.dataset.voteType === data.vote_type) {
                btn.classList.add("voted");
              }
            });
          }
          messageElement.textContent = data.message;
          messageElement.style.color = "#e74c3c";
        }
      } catch (error) {
        console.error("Erreur:", error);
        messageElement.textContent = "Une erreur est survenue lors du vote";
        messageElement.style.color = "#e74c3c";
      }
    });
  });

  // Gestion des commentaires
  const commentForm = document.getElementById("comment-form");
  if (commentForm) {
    commentForm.addEventListener("submit", async function (e) {
      e.preventDefault();
      const debateId = this.dataset.debateId;
      const commentInput = document.getElementById("comment-input");
      const content = commentInput.value.trim();

      if (!content) return;

      try {
        const response = await fetch("comment.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            debate_id: debateId,
            content: content,
          }),
        });

        const data = await response.json();

        if (data.success) {
          // Ajouter le nouveau commentaire au début de la liste
          const commentsContainer =
            document.getElementById("comments-container");
          const noCommentsMessage =
            commentsContainer.querySelector(".no-comments");
          if (noCommentsMessage) {
            noCommentsMessage.remove();
          }

          const commentElement = document.createElement("div");
          commentElement.className = "comment";
          commentElement.innerHTML = `
                        <p>${data.content}</p>
                        <small>Le ${data.created_at}</small>
                    `;
          commentsContainer.insertBefore(
            commentElement,
            commentsContainer.firstChild
          );

          // Réinitialiser le formulaire
          commentInput.value = "";
        } else {
          alert(data.message);
        }
      } catch (error) {
        console.error("Erreur:", error);
        alert("Une erreur est survenue lors de l'ajout du commentaire");
      }
    });
  }

  // Gestionnaire d'événements pour le formulaire de soumission de débat
  const submitDebateForm = document.querySelector("#submit-debate-form");
  if (submitDebateForm) {
    submitDebateForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const formData = new FormData(submitDebateForm);

      try {
        const response = await fetch("submit.php", {
          method: "POST",
          body: formData,
        });

        const data = await response.json();

        if (data.success) {
          window.location.href = "debate.php";
        } else {
          alert(data.message || "Erreur lors de la soumission du débat");
        }
      } catch (error) {
        console.error("Erreur:", error);
      }
    });
  }
});
