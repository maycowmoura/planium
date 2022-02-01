import { useState, useRef } from 'react';
import './style.css';
import api from '../../api';
import { IoPersonAddOutline } from 'react-icons/io5'
import { BsFolderPlus, BsCalendarWeek, BsPatchCheck } from 'react-icons/bs'
import { RiSendPlaneFill } from 'react-icons/ri'


export default function RegisterPlans({ data, setData, setActive }) {
  const [people, setPeople] = useState([]);
  const form = useRef();
  const nameInput = useRef();
  const ageInput = useRef();
  const planInput = useRef();

  function register(e) {
    e.preventDefault();
    const name = nameInput.current.value.trim();
    const age = ageInput.current.value.trim();
    const planId = planInput.current.value.trim();
    document.querySelectorAll('.is-invalid')
      ?.forEach(el => el?.classList.remove('is-invalid'));

    if (!/^[A-zÀ-ú\s]+$/.test(name) || name.length < 3 || name.length > 60) {
      nameInput.current.classList.add('is-invalid');
      return;

    } else if (age === '' || age < 1 || age > 120) {
      ageInput.current.classList.add('is-invalid');
      return;

    } else if (planId === '') {
      planInput.current.classList.add('is-invalid');
      return;
    }


    const newPerson = {
      name, age, planId,
      plan: planInput.current.selectedOptions[0].innerText
    }

    setPeople(people => [...people, newPerson])
    form.current.reset();
  }


  function removePerson(index) {
    setPeople(people => (
      people.filter((person, i) => i !== index)
    ))
  }


  function getBudget() {
    setActive('loadingBudget');

    api.post('/budgets', people)
      .then(resp => {
        if (resp.data.error) {
          alert('Ocorreu um erro. Consulte o console.');
          console.log(resp.data);
          return;
        }

        setData(data => ({ ...data, budget: resp.data }))
        setActive('budget');
      })
      .catch(error => {
        alert('Erro na requisição.');
        console.log(error);
      })
  }


  return (
    <div id="register" className="px-2">
      <form ref={form} className="card p-4">
        <h3><BsFolderPlus /> Cadastrar beneficiários</h3>

          <div className="form-floating mt-3">
            <input
              ref={nameInput}
              type="text"
              className="form-control text-capitalize"
              id="name"
              placeholder="Nome do beneficiário"
              autoComplete="off"
              maxLength="60"
            />
            <div className="invalid-feedback">Preencha o nome corretamente.</div>
            <label htmlFor="name">Nome do beneficiário</label>
          </div>

          <div className="form-floating mt-3">
            <input
              ref={ageInput}
              type="number"
              className="form-control"
              id="age"
              placeholder="Idade"
              min="1"
              max="120"
            />
            <div className="invalid-feedback">Preencha uma idade válida.</div>
            <label htmlFor="name">Idade</label>
          </div>

          <select
            ref={planInput}
            className="form-select mt-3 py-3"
            aria-label="Escolha um plano"
            defaultValue=""
          >
            <option value="">Escolha um plano</option>
            {data.plans.map(plan => (
              <option value={plan.codigo} key={plan.codigo}>{plan.nome}</option>
            ))}
          </select>
          <div className="invalid-feedback">Selecione um plano.</div>

          <button className="btn btn-secondary w-100 mt-3 py-2" onClick={register}>
            <IoPersonAddOutline /> Cadastrar
          </button>
      </form>

      <div className="d-flex justify-content-center align-items-center flex-wrap gap-4 w-100 my-5 pb-5 pb-md-0">
        {!!people.length && people.map((person, i) => (
          <div className="person-card card border-info p-4 w-100" key={i}>
            <h4 className="mb-4 text-capitalize">{person.name}</h4>
            <div><BsCalendarWeek /> {person.age} anos de idade</div>
            <div><BsPatchCheck /> {person.plan}</div>
            <button type="button" className="btn-close" onClick={() => removePerson(i)}></button>
          </div>
        ))}
      </div>

      {!!people.length &&
        <button className="get-budget btn btn-primary btn-lg py-3 me-lg-5 mb-lg-4" onClick={getBudget}>
          <RiSendPlaneFill /> Ver Orçamento
        </button>
      }
    </div>
  );
}