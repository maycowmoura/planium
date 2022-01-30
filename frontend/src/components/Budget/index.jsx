import './style.css';
import { BsCalendarWeek, BsPatchCheck } from 'react-icons/bs';
import { RiArrowGoBackFill } from 'react-icons/ri';

export default function Budget({ data, setData, setActive }) {

  function getPlanById(id) {
    return data.plans.filter(plan => plan.codigo === id)[0].nome;
  }

  function numberToCurrency(number) {
    const formatter = new Intl.NumberFormat('pr-BR', {
      style: 'currency',
      currency: 'BRL',
    })
    return formatter.format(number);
  }

  function newBudget() {
    setData(data => ({...data, budget: null}));
    setActive('register');
  }


  return (
    <div id="budget" className="card px-4 py-5">
      <h2 className="mb-5 text-center">
        Dados do Orçamento
      </h2>

      {data.budget.people.map((person, i) => (
        <div className="person" key={i}>
          <div>
            <h4 className="text-capitalize">{person.name}</h4>
            <div><BsCalendarWeek /> {person.age} anos de idade</div>
            <div><BsPatchCheck /> {getPlanById(person.planId)}</div>
          </div>
          <div className="price">
            {numberToCurrency(person.price)}
          </div>
        </div>
      ))}

      <div className="fs-5 fw-bold text-center mt-5">
        Total Geral: {numberToCurrency(data.budget.total)}
      </div>

      <div className="text-center">
        <button className="btn btn-info mt-5" onClick={newBudget}>
          <RiArrowGoBackFill /> Novo Orçamento
        </button>
      </div>
    </div>
  );
}